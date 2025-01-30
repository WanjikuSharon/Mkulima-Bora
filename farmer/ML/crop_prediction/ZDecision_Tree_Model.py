#!/usr/bin/env python
# coding: utf-8

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from collections import Counter

# Load the dataset
data = pd.read_csv(r'C:\xampp\htdocs\Projects\agriculture-portal\farmer\ML\crop_prediction\preprocessed2.csv')

# Inspect the first few rows of the dataset
print(data.head())

# Remove trailing whitespace from the 'Season' column
data['Season'] = data['Season'].str.rstrip()

# Handle missing or invalid values in the 'Season' column
# Drop rows with missing values in 'Season'
data = data.dropna(subset=['Season'])

# Ensure all values in the 'Season' column are strings
data['Season'] = data['Season'].astype(str)

# Print the length of each value in the 'Season' column
for i in data['Season']:
    print(len(i))  # Ensure all values are processed without errors

# List all column names
print(list(data))

# Remove the 'Unnamed: 0' column if it exists
if 'Unnamed: 0' in data.columns:
    del data['Unnamed: 0']

# Convert the data to a NumPy array for further processing
training_data = list(np.array(data))

# Take a subset of rows as testing data
testing_data = training_data[100:120]  # Rows from 100 to 120

# Define the header for better interpretability
header = ['State_Name', 'District_Name', 'Season', 'Crop']

# Define a function to get unique values in a specific column
def unique_vals(Data, col):
    return set([row[col] for row in Data])

# Define a function to count occurrences of each class in the data
def class_counts(Data):
    counts = {}
    for row in Data:
        label = row[-1]
        if label not in counts:
            counts[label] = 0
        counts[label] += 1
    return counts

# Display class counts for the training data
print(class_counts(training_data))

# Define a Question class for the decision tree
class Question:
    def __init__(self, column, value):
        self.column = column
        self.value = value

    def match(self, example):
        val = example[self.column]
        return val == self.value

    def match2(self, example):
        if example == 'True' or example == 'true' or example == '1':
            return True
        else:
            return False

    def __repr__(self):
        return f"Is {header[self.column]} == {str(self.value)}?"

# Define a function to partition the data based on a question
def partition(Data, question):
    true_rows, false_rows = [], []
    for row in Data:
        if question.match(row):
            true_rows.append(row)
        else:
            false_rows.append(row)
    return true_rows, false_rows

# Calculate the Gini impurity
def gini(Data):
    counts = class_counts(Data)
    impurity = 1
    for lbl in counts:
        prob_of_lbl = counts[lbl] / float(len(Data))
        impurity -= prob_of_lbl ** 2
    return impurity

# Calculate information gain
def info_gain(left, right, current_uncertainty):
    p = float(len(left)) / (len(left) + len(right))
    return current_uncertainty - p * gini(left) - (1 - p) * gini(right)

# Find the best split for the data
def find_best_split(Data):
    best_gain = 0
    best_question = None
    current_uncertainty = gini(Data)
    n_features = len(Data[0]) - 1
    for col in range(n_features):
        values = unique_vals(Data, col)
        for val in values:
            question = Question(col, val)
            true_rows, false_rows = partition(Data, question)
            if len(true_rows) == 0 or len(false_rows) == 0:
                continue
            gain = info_gain(true_rows, false_rows, current_uncertainty)
            if gain > best_gain:
                best_gain, best_question = gain, question
    return best_gain, best_question

# Define a leaf node
class Leaf:
    def __init__(self, Data):
        self.predictions = class_counts(Data)

# Define a decision node
class Decision_Node:
    def __init__(self, question, true_branch, false_branch):
        self.question = question
        self.true_branch = true_branch
        self.false_branch = false_branch

# Build the decision tree recursively
def build_tree(Data, i=0):
    gain, question = find_best_split(Data)
    if gain == 0:  # Contains same data
        return Leaf(Data)
    true_rows, false_rows = partition(Data, question)
    true_branch = build_tree(true_rows, i)
    false_branch = build_tree(false_rows, i)
    return Decision_Node(question, true_branch, false_branch)

# Build the tree and print it
my_tree = build_tree(training_data)
print(my_tree)

# Print the decision tree
def print_tree(node, spacing=""):
    if isinstance(node, Leaf):
        print(spacing + "Predict", node.predictions)
        return
    print(spacing + str(node.question))
    print(spacing + "--> True:")
    print_tree(node.true_branch, spacing + "  ")
    print(spacing + "--> False:")
    print_tree(node.false_branch, spacing + "  ")

print_tree(my_tree)

# Print the probabilities for predictions
def print_leaf(counts):
    total = sum(counts.values()) * 1.0
    probs = {}
    for lbl in counts.keys():
        probs[lbl] = f"{int(counts[lbl] / total * 100)}%"
    return probs

# Classify a new row based on the decision tree
def classify(row, node):
    if isinstance(node, Leaf):
        return node.predictions
    if node.question.match(row):
        return classify(row, node.true_branch)
    else:
        return classify(row, node.false_branch)

# Save the model using joblib
from sklearn.externals import joblib

# Save the model as a pickle in a file
joblib.dump(my_tree, 'filetest2.pkl')
