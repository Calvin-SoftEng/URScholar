# train_model.py
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report
from joblib import dump

# 1. Load your dataset
# Example: columns: grade, income, year_level, course, recommended
data = pd.read_csv("data.csv")

# Split features/target (last column = target)
X = data.iloc[:, :-1]   # all columns except last
y = data.iloc[:, -1]    # last column as label

# 2. Train / test split
X_train, X_test, Y_train, Y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

# 3. Build Random Forest model
model = RandomForestClassifier(
    n_estimators=200,
    max_depth=None,
    random_state=42
)

# 4. Train
model.fit(X_train, Y_train)

# 5. Evaluate (optional, just to check performance)
y_pred = model.predict(X_test)
print(classification_report(Y_test, y_pred))

# 6. Save model + column order (for later use in API)
dump(model, "rf_model.joblib")
dump(list(X.columns), "feature_columns.joblib")

print("Model trained and saved as rf_model.joblib")
