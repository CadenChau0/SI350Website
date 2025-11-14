import pandas as pd



df = pd.read_csv("bets.csv", sep=";")

# Compute winnings per sport (sum of gain)
winnings_by_sport = df.groupby("sport")["gain"].sum().reset_index()

winnings_by_sport.to_json("winnings.json", orient="records")
