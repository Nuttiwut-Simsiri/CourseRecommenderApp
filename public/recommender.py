import csv,json,warnings,argparse,timeit
import numpy as np
warnings.filterwarnings("ignore", category=np.VisibleDeprecationWarning)
warnings.simplefilter(action = "ignore", category = RuntimeWarning)
import matplotlib.pyplot as plt
from pprint import pprint
from scikits.crab import datasets
from scikits.crab.models import MatrixPreferenceDataModel,MatrixBooleanPrefDataModel
from scikits.crab.metrics.pairwise import cosine_distances,pearson_correlation,euclidean_distances
from scikits.crab.similarities import UserSimilarity,ItemSimilarity
from scikits.crab.recommenders.knn import UserBasedRecommender,ItemBasedRecommender
from scikits.crab.recommenders.knn.neighborhood_strategies import NearestNeighborsStrategy
from scikits.crab.recommenders.knn.item_strategies import ItemsNeighborhoodStrategy
from scikits.crab.models.utils import UserNotFoundError
from scikits.crab.metrics.classes import CfEvaluator
from sklearn.base import BaseEstimator
from datetime import datetime
import mysql.connector
from mysql.connector import Error
class Bunch(dict):
    """
    Container object for datasets: dictionary-like object
    that exposes its keys and attributes. """

    def __init__(self, **kwargs):
        dict.__init__(self, kwargs)
        self.__dict__ = self

def create_dataset_csv(dataset_name):
    dataset = {} # define a dictionary
    with open(dataset_name) as myfile:
        reader = csv.DictReader(myfile, delimiter=',')
        i = 0

        for line in reader:
            i += 1
            if (int(line['user_id']) not in dataset):
                dataset[int(line['user_id'])] = {}
            dataset[int(line['user_id'])][int(line['item_id'])] = float(line['rating'])
    return dataset


def find_by_id_item(arg,value):
    for k, v in arg.items():
        if str(value) == str(v):
            return k
def find_by_id_user(arg,value):
    for k, v in arg.items():
        if str(value) == str(v):
            return k

def create_dataset_data(dataset_name,List_item,List_user):
    dataset = {} # define a dictionary
    i = 0
    for line in dataset_name:
        i += 1
        user = find_by_id_user(List_user,line['user_id'])
        item = find_by_id_item(List_item,line['item_id'])

        if user == None:
            break
        if (user not in dataset):
            dataset[user] = {}

        dataset[user][item] = float(line['rating'])



    return dataset

def create_dataset_item(dataset_name):
    dataset = {} # define a dictionary
    i = 0
    for line in dataset_name:
        i += 1
        dataset[i] = str(line['item_id'])
    return dataset

def create_dataset_user(dataset_name):
    dataset = {} # define a dictionary
    i = 0
    for line in dataset_name:
        i += 1
        dataset[i] = str(line['user_id'])
    return dataset

def create_dataset_item_name(dataset_name):
    dataset = {} # define a dictionary
    i = 0
    for line in dataset_name:
        i += 1
        dataset[i] = str(line['item_name'])
    return dataset

def connect(number_training,active_user,student_ID):

    """ Connect to MySQL database """
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='cra-project',
                                       user='root',
                                       password='')
        if conn.is_connected():
            cur = conn.cursor()

            query = ("SELECT * FROM user_rating ")
            cur.execute(query)
            rating = cur.fetchall()


            query = ("SELECT DISTINCT course_id FROM courses ORDER BY course_id ASC")
            cur.execute(query)
            courses = cur.fetchall()


            query = ("SELECT DISTINCT student_id FROM user_item_rating where id <= %i ORDER BY id ASC" % (number_training))
            cur.execute(query)
            users = cur.fetchall()


            query = ("SELECT DISTINCT course_name FROM courses ORDER BY course_id ASC")
            cur.execute(query)
            courses_name = cur.fetchall()


            query = ("SELECT student_id FROM user_item_rating where student_id = %s  ORDER BY id ASC"%(student_ID))
            cur.execute(query)
            present_user = cur.fetchall()
            cur.close()


    except Error as e:
        print(e)

    finally:
        return rating,courses,users,courses_name,present_user
        conn.close()
def create_finally_dataset(number_training,active_user,student_ID):
    Database  = connect(number_training,active_user,student_ID)


    empList_rating = []
    for emp in Database[0]:

        empDict = {
            'user_id': emp[0].encode('utf-8'),
            'item_id': emp[1].encode('utf-8'),
            'rating': emp[2]
            }
        empList_rating.append(empDict)

    empList_course = []
    for emp in Database[1]:
        empDict = {
            'item_id': emp[0],
            }
        empList_course.append(empDict)

    empList_user = []
    for emp in Database[2]:
        empDict = {
            'user_id': emp[0],
            }
        empList_user.append(empDict)
    for emp in Database[4]:
        empDict = {
            'user_id': emp[0],
            }
        empList_user.append(empDict)

    empList_course_name = []
    for emp in Database[3]:
        empDict = {
            'item_name': emp[0],
            }
        empList_course_name.append(empDict)

    items = create_dataset_item(empList_course)
    users = create_dataset_user(empList_user)
    data = create_dataset_data(empList_rating,items,users)
    items_name = create_dataset_item_name(empList_course_name)
    return Bunch(data=data, item_ids=items,
                 user_ids=users, items_name=items_name,DESCR='course recommender app')
def recommend_list_to_json(recommend_list,Dataset,type="ITEM"):
    myDataset_course = Dataset
    if type=="ITEM" :
        output_string = "ITEM:"
    elif type=="USER":
        output_string = ":USER:"
    item_name = myDataset_course.items_name
    for item in recommend_list:
        output_string += "%s/%s/" % (item_name[item[0]],float(item[1]))
    return  output_string


def find_new_user_id(Dataset,student_ID):
    user_list = myDataset_course.user_ids
    for key, val in user_list.iteritems():
        if val == student_ID:
            return key

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Welcome to Recommender system')
    parser.add_argument('Active_user',type=int,help='Insert id of user to recommend :')
    parser.add_argument('student_ID',type=int,help='Insert student_id of user to recommend :')
    args = parser.parse_args()
    active_user = int(args.Active_user)
    student_ID = int(args.student_ID)

    #start_time = timeit.default_timer()
    myDataset_course = create_finally_dataset(50,active_user,student_ID)
    #pprint(myDataset_course)
    new_active_user_id = find_new_user_id(myDataset_course,str(student_ID))
    model = MatrixPreferenceDataModel(myDataset_course['data'])
    similarity_item = ItemSimilarity(model,pearson_correlation)
    neighborhood_item = ItemsNeighborhoodStrategy()
    recsys_item = ItemBasedRecommender(model, similarity_item, neighborhood_item, with_preference=True)
    #recommend_top_5_item  = recsys_item.recommended_because(new_active_user_id,42,how_many=5)
    recommend_list_item = recsys_item.recommend(new_active_user_id,how_many=5)
    #print("Item : " +recommend_list_to_json(recommend_list_item))
    #evaluator = CfEvaluator()
    #test_item_a = evaluator.evaluate_on_split(recsys_item,at=4, sampling_ratings=0.5,permutation=False,cv=5)
    #pprint(test_item_a)
    #elapsed = timeit.default_timer() - start_time
    #print elapsed

    #start_time = timeit.default_timer()
    myDataset_course = create_finally_dataset(150,active_user,student_ID)
    #pprint(myDataset_course)
    new_active_user_id = find_new_user_id(myDataset_course,str(student_ID))
    model = MatrixPreferenceDataModel(myDataset_course['data'])
    similarity_user = UserSimilarity(model,cosine_distances)
    neighborhood_user = NearestNeighborsStrategy()
    recsys_user = UserBasedRecommender(model, similarity_user, neighborhood_user,with_preference=True)
    recommend_list_user = recsys_user.recommend(new_active_user_id,how_many=5)
    #elapsed = timeit.default_timer() - start_time
    #print elapsed
    print recommend_list_to_json(recommend_list_item,myDataset_course,type="ITEM")+recommend_list_to_json(recommend_list_user,myDataset_course,type="USER")
    #evaluator = CfEvaluator()
    #test_user_a = evaluator.evaluate_on_split(recsys_user,at=4,sampling_ratings=0.7,permutation=False,cv=5)
    #pprint(test_user_a)
