import json,numpy,math,scipy.stats,argparse
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
import mysql.connector
from mysql.connector import MySQLConnection, Error

def query_with_fetchall():
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='cra-project',
                                       user='root',
                                       password='')

        cursor = conn.cursor()
        cursor.execute("SELECT * FROM user_item_rating")

        rows = cursor.fetchall()

        #print('Total Row(s):', cursor.rowcount)
        Number_of_courses = 46
        Number_of_users= cursor.rowcount
        training_matrix = numpy.zeros((Number_of_users,Number_of_courses))
        user_id = numpy.zeros(Number_of_users)
        course_counter = 0
        for row in range(cursor.rowcount):
            course_counter = 0
            for col in range(2,Number_of_courses):
                training_matrix[row][course_counter] = rows[row][col]
                user_id[row] = rows[row][1]
                course_counter +=1;
    except Error as e:
        print(e)

    finally:
        cursor.close()
        conn.close()
        return training_matrix,Number_of_courses,Number_of_users,user_id
def query_course_name():
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='cra-project',
                                       user='root',
                                       password='')

        cursor = conn.cursor()
        cursor.execute("SELECT  DISTINCT course_name FROM courses ORDER BY `courses`.`course_id` ASC")

        rows = cursor.fetchall()

        #print('Total Row(s):', cursor.rowcount)
        Number_of_courses = cursor.rowcount
        course_counter = 0
        courseList=[]
        for row in range(46):
            courseList.append(rows[row][0])
    except Error as e:
        print(e)

    finally:
        cursor.close()
        conn.close()
        return courseList



#print(training_matrix)

def create_similarity_matrix(data,Active_user,type="user"):
    Nsimilarity_matrix_user = np.zeros(Number_of_users)
    if type=="user":
        for user in range(Number_of_users):
            r,p = scipy.stats.pearsonr(training_matrix[Active_user],training_matrix[user])
            if r == 1 :
                Nsimilarity_matrix_user[user] = 0
            else :
                Nsimilarity_matrix_user[user] = r
        return Nsimilarity_matrix_user
    elif type=="item":
        for course in range(Number_of_courses):
            for courses in range(Number_of_courses):
                #print("item : item => %s : %s "% (i,user))
                #print(np.transpose(data[:,course]))
                '''
                r,p = scipy.stats.pearsonr(np.transpose(data[:,course]),np.transpose(data[:,courses]))
                if r == 1 :
                    Nsimilarity_matrix_item[course][courses] = 0
                else :
                    Nsimilarity_matrix_item[course][courses] = r
        return Nsimilarity_matrix_item
        '''
most_similaritylist=[]
def Recommender(Active_user,neighborhood_size):
    similarity_matrix = create_similarity_matrix(training_matrix,Active_user,type="user")
    # Calculate Most similarity user
    similarity_index = numpy.argsort(-similarity_matrix,axis=None,kind='quicksort')
    similarity_index = similarity_index[0:neighborhood_size]
    for user in similarity_index:
        #print("%s => %f " % (user_id_array[user],similarity_matrix[user]))
        most_similaritylist.append(similarity_matrix[user])

    top = 0
    sum_similarity = 0.0001
    # Create rating matrix
    ratings = numpy.array(training_matrix)
    # Create similarity_matrix of active user and other user
    similarity = numpy.array(most_similaritylist)
    # Calculate Mean rating of active user
    ratings_Active_user = ratings[Active_user]
    ratings_mean_Active_user = numpy.mean(ratings_Active_user[ratings_Active_user != 0])
    # index of item without ratings
    index_without_rating = numpy.where(ratings_Active_user == 0)[0]
    # new predicted_rating
    new_ratings_Active_user = numpy.zeros(len(index_without_rating))
    neighborhood_counter = 0
    item_counter = 0
    #print(similarity_index)
    #print(index_without_rating)
    for item in index_without_rating:
        for user in similarity_index:
            if ratings[user][item] != 0 and neighborhood_counter < neighborhood_size:
                ratings_Other_user = ratings[user]
                ratings_mean_Other_user = numpy.mean(ratings_Other_user[ratings_Other_user != 0 ])
                top += similarity[neighborhood_counter]*(ratings[user][item] - ratings_mean_Other_user)
                sum_similarity += abs(similarity[neighborhood_counter])
                #print(" item: %s Average rating of %s => %f   similarity: %s" %(course_name_array[item],user,ratings_mean_Other_user,similarity[neighborhood_counter]))
                neighborhood_counter += 1
        Nprediction = ratings_mean_Active_user +  (top / sum_similarity )
        #print(ratings_mean_Active_user,Nprediction)
        new_ratings_Active_user[item_counter] = Nprediction
        item_counter +=1
        Nprediction,top,sum_similarity,neighborhood_counter = 0,0,0.0001,0
    new_ratings_index = numpy.argsort(-new_ratings_Active_user,axis=None,kind='quicksort')
    #print("Recommender for %s" % (user_id_array[Active_user]))
    String_data = ""
    item_counters = 0
    #print(new_ratings_index)
    for item in new_ratings_index:
        item_counters += 1
        if item_counters == 5:
            String_data += "%s:%s" % (course_name_array[index_without_rating[item]],round(new_ratings_Active_user[item],2))
        elif item_counters < 5:
            String_data += "%s:%s:" % (course_name_array[index_without_rating[item]],round(new_ratings_Active_user[item],2))
    print(String_data)
    return  String_data

if __name__ == "__main__":
    course_name_array =  query_course_name()
    training_matrix,Number_of_courses,Number_of_users,user_id_array = query_with_fetchall()
    parser = argparse.ArgumentParser(description='Welcome to Recommender system')
    parser.add_argument('Active_user',type=int,help='Insert index of user to recommend :')
    args = parser.parse_args()
    #print(training_matrix)
    similarity_matrix = numpy.zeros((Number_of_users,Number_of_users))
    Active_user_index = np.where(user_id_array == args.Active_user)[0]
    Json = Recommender(2,15)
    print(Json)










"""

#create Rating Matrix
Number_of_movies = 6
Number_of_users= 7
training_matrix = numpy.zeros((Number_of_users,Number_of_movies))
movies = ['Lady in the Water','Snakes on a Plane','Just My Luck','Superman Returns','You, Me and Dupree','The Night Listener']
users = ['Lisa Rose','Gene Seymour','Michael Phillips','Claudia Puig','Mick LaSalle','Jack Matthews','Toby']

#Calculate Similarity Cosine

similarity_matrix = numpy.zeros(7)
def create_similarity_matrix(Active_user):
    for u in range(Number_of_users):
        r,p = scipy.stats.pearsonr(training_matrix[Active_user],training_matrix[u])
        if r == 1 :
            similarity_matrix[u] = 0
        else :
            similarity_matrix[u] = r
    return similarity_matrix

most_similaritylist=[]
def Recommender(Active_user,neighborhood_size):
    create_similarity_matrix(Active_user)
    # Calculate Most similarity user
    similarity_index = numpy.argsort(-similarity_matrix,axis=None,kind='quicksort')
    similarity_index = similarity_index[0:neighborhood_size]
    for u in similarity_index:
        #print("%s => %f " % (users[u],similarity_matrix[u]))
        most_similaritylist.append(similarity_matrix[u])

    top = 0
    sum_similarity = 0
    # Create rating matrix
    ratings = numpy.array(training_matrix)
    # Create similarity_matrix of active user and other user
    similarity = numpy.array(most_similaritylist)
    # Calculate Mean rating of active user
    ratings_Active_user = ratings[Active_user]
    ratings_mean_Active_user = numpy.mean(ratings_Active_user[ratings_Active_user != 0])
    # index of item without ratings
    index_without_rating = numpy.where(ratings_Active_user == 0)[0]
    # new predicted_rating
    new_ratings_Active_user = numpy.zeros(len(index_without_rating))
    neighborhood_counter = 0
    item_counter = 0
    #print(similarity_index)
    for item in index_without_rating:
        for user in similarity_index:
            if ratings[user][item] != 0 and neighborhood_counter < neighborhood_size:
                ratings_Other_user = ratings[user]
                ratings_mean_Other_user = numpy.mean(ratings_Other_user[ratings_Other_user != 0 ])
                top += similarity[neighborhood_counter]*(ratings[user][item] - ratings_mean_Other_user)
                sum_similarity += abs(similarity[neighborhood_counter])
                #print(" item: %s Average rating  %f  user  %s  similarity: %s" %(item,ratings_mean_Other_user,user,similarity[neighborhood_counter]))
                neighborhood_counter += 1
        Nprediction = ratings_mean_Active_user +  (top / sum_similarity )
        new_ratings_Active_user[item_counter] = Nprediction
        item_counter +=1
        Nprediction,top,sum_similarity,neighborhood_counter = 0,0,0,0
    #print(new_ratings_Active_user)
    new_ratings_index = numpy.argsort(-new_ratings_Active_user,axis=None,kind='quicksort')
    #print("Recommender for %s" % (users[Active_user]))
    String_data = ""
    item_counter = 0
    for item in new_ratings_index:
        item_counter += 1
        if item_counter == len(new_ratings_index):
            String_data += "%s:%s" % (movies[index_without_rating[item]],round(new_ratings_Active_user[item],2))
        elif item_counter < len(new_ratings_index):
            String_data += "%s:%s:" % (movies[index_without_rating[item]],round(new_ratings_Active_user[item],2))

    return  String_data
if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Welcome to Recommender system')
    parser.add_argument('Active_user',type=int,help='Insert index of user to recommend :')
    parser.add_argument('neighborhood_size',type=int,help='Insert neighborhood_size :')
    args = parser.parse_args()
    for user in range(len(users)):
        for movie in range(len(movies)):
                training_matrix[user][movie] = dataset[users[user]][movies[movie]]
    #print(training_matrix)
    Json = Recommender(args.Active_user,args.neighborhood_size)
    print(Json)
"""
