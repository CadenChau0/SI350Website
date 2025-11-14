import pandas as pd



df = pd.read_csv("bets.csv", sep=";")

winnings_by_sport = df.groupby("sport")["gain"].sum().reset_index()

winnings_by_sport = winnings_by_sport.sort_values(by="gain", ascending=False)

winnings_by_sport.to_json("winnings.json", orient="records")
