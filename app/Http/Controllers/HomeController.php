<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Sentinel;
use Response;
use App\User;
use View;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class HomeController extends Controller
{

  public function render_welcome()
  {
        $rating2grade = array('4' =>'A','3.5' =>'B+','3' => 'B','2.5' =>'C+','2' =>'C','1.5' =>'D+','1' =>'D','0' =>'F');
        $course_list = DB::table('user_rating')->where('student_ID', '=',Sentinel::getUser()->student_ID)->get();
        $table ="";
        for($i = 0; $i< sizeof($course_list);$i++){
            $course_name = DB::table('courses')->where('course_id', '=',$course_list[$i]->course_ID )->pluck('course_name');
            $table .= '
            <tr>
              <td style="width:3%" id="id" data-title="#">'.(string)($i+1).'</td>
              <td style="width:10%" id="course_id" data-title="COURSE ID">'.$course_list[$i]->course_ID.'</td>
              <td style="width:10%" id="course_name" data-title="COURSE NAME">'.$course_name[0].'</td>
              <td style="width:3%" id="rating" data-title="GRADE">'.$rating2grade[$course_list[$i]->rating].'</td>
              <td style="width:10%" data-title="REMOVE"><button id="btn_remove" data-id3="'.$course_list[$i]->course_ID.','.$course_name[0].'" class="w3-btn w3-red"> REMOVE  </button> </td>
            </tr>';
        }
        $table .=
                  '
                  </tbody>
                </table><br>
                  ';

        return View::make('welcome')->with('table', $table);

  }
  public function render_recommender()
  {
    return view('recommender');
  }
  public function render_Add_course()
  {
    return view('Add_course');
  }

  public function query_course(Request $req){
    $key = $req->Key_search;
    if(ctype_alpha($key)){
      $course_list = DB::table('courses')->where('course_name', 'LIKE',"%$key%")->get();
    }elseif(is_numeric($key)) {
      $course_list = DB::table('courses')->where('course_id', 'LIKE',"%$key%")->get();
    }
    return response()->json($course_list);
  }
  public function insert_course(Request $req)
  {
    $this->validate($req,User::$course_rules);
    $Data= array();
    $DataUpdate = array();
    //insert to user_item_rating
    $DataInsert['student_ID'] = Sentinel::getUser()->student_ID;
    $DataInsert['course_ID'] = $req->course_id;
    $DataInsert['rating'] = $req->course_grade;
    //Update user_item_rating
    $course_id = "0".$req->course_id;
    $DataUpdate[$course_id] = $req->course_grade;
    //insert to user_rating
    $Data['student_id'] = Sentinel::getUser()->student_ID;
    $Data[$course_id] = $req->course_grade;
    try{
      $student_users = DB::table('user_item_rating')->where('student_id', '=',Sentinel::getUser()->student_ID )->pluck('id');
      if(sizeof($student_users) == 0){
        DB::table('user_item_rating')->insert($Data);
      }elseif(sizeof($student_users) != 0){
        DB::table('user_item_rating')
          ->where('student_id',Sentinel::getUser()->student_ID)
          ->update($DataUpdate);
      }

    } catch(\Exception $e){
      return response()->json($e->errorInfo);
    }

    DB::table('user_rating')->insert($DataInsert);
    return response()->json("ADD ".$req->course_name." SUCCESSFULLY !!");
  }

  public function query(){
    $result = array();
    $course_list= DB::table('user_rating')->where('student_ID', '=',Sentinel::getUser()->student_ID)->get();
    for($i = 0; $i< sizeof($course_list);$i++) {
      $course_name = DB::table('courses')->where('course_id', '=',$course_list[$i]->course_ID )->pluck('course_name');
      $result[$i]['course_id'] = $course_list[$i]->course_ID;
      $result[$i]['course_name'] = $course_name[0];
      $result[$i]['rating'] = $course_list[$i]->rating;
    }

    return response()->json($result,200);
  }
  public function remove_course(Request $req)
  {
    try{
        DB::table('user_rating')->where([
          ['student_ID', '=', Sentinel::getUser()->student_ID],
          ['course_ID', '=', $req->course_id]
          ])->delete();
      }catch(\Exception $e){
          return response()->json($e);
      }
      return response()->json('REMOVE '.$req->course_name.' SUCCESSFULLY !!');
  }
  public function recommend()
  {
    $student_users = DB::table('user_item_rating')->where('student_id', '=',Sentinel::getUser()->student_ID )->orderBy('id', 'asc')->pluck('id');
    if (sizeof($student_users) == 0){
      Session::flash('message', "Please add Course before recommended !");
      return redirect('/welcome');
    }elseif(sizeof($student_users) != 0){
        $process = new Process('python recommender.py '.(string)$student_users[0].' '.Sentinel::getUser()->student_ID);
        $process->run();

         // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $result_arr = array();
        $item_arr = array();
        $newArray_I = array();
        $newArray_U = array();
        $output = $process->getOutput();
        $temp_data = explode(":",$output);
        $rank = 0;
        $temp_course = explode("/",$temp_data[1]);

        $rank2grade = array('0' =>'4.0','1' =>'3.5','2' => '3.0','3' =>'2.5','4' =>'2.0');
        for($i=0; $i< sizeof($temp_course)-1 ;$i +=2)
        {
          if ($temp_course[$i+1] == "nan") {
              $newArray_I[$rank] = array('course_name' => $temp_course[$i], 'rating' => $rank2grade[$rank]);
              $rank++;
          }elseif ($temp_course[$i+1] != "nan") {

              $newArray_I[$rank] = array('course_name' => $temp_course[$i], 'rating' => $temp_course[$i+1]);
              $rank++;
          }

        }

        function build_sorter($key) {
          return function ($a, $b) use ($key) {
            return strcasecmp($a[$key], $b[$key]);
          };
        }
        usort($newArray_I, build_sorter('rating'));
        $result_arr['ITEM'] = array_reverse($newArray_I);
        $temp_course_u = explode("/",$temp_data[3]);
        $rank = 0;
        for($i=0; $i< sizeof($temp_course_u)-1 ;$i +=2)
        {
          if ($temp_course_u[$i+1] == "nan") {
            $newArray_U[$rank] = array('course_name' => $temp_course_u[$i], 'rating' => $rank2grade[$rank]);
            $rank +=1;
          }else{
            $newArray_U[$rank] = array('course_name' => $temp_course_u[$i], 'rating' => $temp_course_u[$i+1]);
            $rank +=1;
          }


        }
        $result_arr['USER'] = $newArray_U;
        $json_data = json_encode($result_arr);
        return response()->json($json_data,200);
      }
  }

}
