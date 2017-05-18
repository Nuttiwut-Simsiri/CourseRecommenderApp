import csv,json,warnings,argparse
import numpy as np
warnings.filterwarnings("ignore", category=np.VisibleDeprecationWarning)
warnings.simplefilter(action = "ignore", category = RuntimeWarning)
import matplotlib.pyplot as plt
from pprint import pprint
from scikits.crab import datasets
from scikits.crab.models import MatrixPreferenceDataModel,MatrixBooleanPrefDataModel
from scikits.crab.metrics.pairwise import cosine_distances,euclidean_distances,jaccard_coefficient,pearson_correlation
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

def connect():

    """ Connect to MySQL database """
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='cra-project',
                                       user='root',
                                       password='')
        if conn.is_connected():
            cur = conn.cursor()
            query = ("SELECT * FROM user_rating")
            cur.execute(query)
            rating = cur.fetchall()
            query = ("SELECT DISTINCT course_id FROM courses ORDER BY course_id ASC")
            cur.execute(query)
            courses = cur.fetchall()
            query = ("SELECT DISTINCT student_id FROM user_item_rating ORDER BY id ASC")
            cur.execute(query)
            users = cur.fetchall()
            query = ("SELECT DISTINCT course_name FROM courses ORDER BY course_id ASC")
            cur.execute(query)
            courses_name = cur.fetchall()
            cur.close()


    except Error as e:
        print(e)

    finally:
        return rating,courses,users,courses_name
        conn.close()
def create_finally_dataset():
    Database  = connect()
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
def recommend_list_to_json(recommend_list,type="ITEM"):
    myDataset_course = create_finally_dataset()
    if type=="ITEM" :
        output_string = "ITEM:"
    elif type=="USER":
        output_string = ":USER:"
    item_name = myDataset_course.items_name
    for item in recommend_list:
        output_string += "%s/%s/" % (item_name[item[0]],float(item[1]))
    return  output_string

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Welcome to Recommender system')
    parser.add_argument('Active_user',type=int,help='Insert id of user to recommend :')
    args = parser.parse_args()
    active_user = int(args.Active_user)
    myDataset_course = create_finally_dataset()

    model = MatrixPreferenceDataModel(myDataset_course['data'])
    #pprint(myDataset_course)
    similarity_item = ItemSimilarity(model, cosine_distances)
    neighborhood_item = ItemsNeighborhoodStrategy()
    recsys_item = ItemBasedRecommender(model, similarity_item, neighborhood_item, with_preference=True)
    #recommend_top_5_item  = recsys_item.recommended_because(active_user,7,how_many=5)
    recommend_list_item = recsys_item.recommend(active_user,how_many=5)
    #print("Item : " +recommend_list_to_json(recommend_top_5_item))

    model = MatrixPreferenceDataModel(myDataset_course['data'])
    similarity_user = UserSimilarity(model, cosine_distances)
    neighborhood_user = NearestNeighborsStrategy()
    recsys_user = UserBasedRecommender(model, similarity_user, neighborhood_user,with_preference=True)
    recommend_top_5_user = recsys_user.recommended_because(active_user,2,how_many=5)
    recommend_list_user = recsys_user.recommend(active_user,how_many=5)
    #print("Top 5 user: "+recommend_list_to_json(recommend_top_5_user))
    #print(type(recommend_list_item))
    print   recommend_list_to_json(recommend_list_item,type="ITEM")+recommend_list_to_json(recommend_list_user,type="USER")
