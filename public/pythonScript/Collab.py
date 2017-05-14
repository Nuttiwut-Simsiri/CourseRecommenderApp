import json,numpy,math,scipy.stats,argparse,http.client, urllib.parse
from sklearn.metrics.pairwise import cosine_similarity

dataset  = {'Lisa Rose': {
'Lady in the Water': 2.5,
'Snakes on a Plane': 3.5,
'Just My Luck': 3.0,
'Superman Returns': 3.5,
'You, Me and Dupree': 2.5,
'The Night Listener': 3.0},
'Gene Seymour': {'Lady in the Water': 3.0,
'Snakes on a Plane': 3.5,
'Just My Luck': 1.5,
'Superman Returns': 5.0,
'You, Me and Dupree': 3.5,
'The Night Listener': 3.0},

'Michael Phillips': {'Lady in the Water': 2.5,
'Snakes on a Plane': 3.0,
'Just My Luck': 0,
'Superman Returns': 3.5,
'You, Me and Dupree': 0,
'The Night Listener': 4.0},

'Claudia Puig': {'Lady in the Water': 0,
'Snakes on a Plane': 3.5,
'Just My Luck': 3.0,
'Superman Returns': 4.0,
'You, Me and Dupree': 2.5,
'The Night Listener': 4.5,
},

'Mick LaSalle': {'Lady in the Water': 3.0,
'Snakes on a Plane': 4.0,
'Just My Luck': 2.0,
'Superman Returns': 3.0,
'You, Me and Dupree': 2.0,
'The Night Listener': 3.0},

'Jack Matthews': {
'Lady in the Water': 0,
'Snakes on a Plane': 4.0,
'Just My Luck': 0,
'Superman Returns': 5.0,
'You, Me and Dupree': 3.5,
'The Night Listener': 3.0},

'Toby': {
'Lady in the Water': 0,
'Snakes on a Plane': 4.5,
'Just My Luck': 0,
'Superman Returns':4.0,
'You, Me and Dupree':0,
'The Night Listener': 0
} }



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
    recommend_list = []
    for item in new_ratings_index:
        temp = dict()
        #print("Movie:  %s  rating: %s" % (movies[index_without_rating[item]],new_ratings_Active_user[item]))
        temp['movie_name'] = movies[index_without_rating[item]]
        temp['rating'] = round(new_ratings_Active_user[item],2)
        recommend_list.append(temp)
    jsonRecommend = json.dumps(recommend_list)
    #jsonRecommend = json.dumps(temp)
    return jsonRecommend
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
    json_data = json.loads(Json)
    print(json_data)
