import pandas as pd
import json



df = pd.read_csv("bets.csv", sep=";")

winnings_by_sport = df.groupby("sport")["gain"].sum().reset_index()

winnings_by_sport = winnings_by_sport.sort_values(by="gain", ascending=False)

winnings_by_sport.to_json("winnings.json", orient="records")

num_bets = len(df)
num_wins = df['is_win'].sum()
win_rate = num_wins / num_bets

total_stake = df['stake'].sum()
total_ggr = df['GGR'].sum()
profit = -total_ggr
roi = profit / total_stake

by_sport = (df.groupby('sport').agg(
          num_bets=('bet_id', 'count'),
          num_wins=('is_win', 'sum'),
          total_stake=('stake', 'sum'),
          total_ggr=('GGR', 'sum'),
          avg_odds=('odds', 'mean')
      )
      .reset_index()
)

by_sport['win_rate'] = by_sport['num_wins'] / by_sport['num_bets']
by_sport['profit'] = -by_sport['total_ggr']
by_sport['roi'] = by_sport['profit'] / by_sport['total_stake']


for col in ['total_stake', 'total_ggr', 'avg_odds', 'win_rate', 'profit', 'roi']:
    by_sport[col] = by_sport[col].round(3)

sports_by_profit = by_sport.sort_values('profit', ascending=False).to_dict(orient='records')
sports_by_volume = by_sport.sort_values('num_bets', ascending=False).to_dict(orient='records')


by_type = (df.groupby('bet_type').agg(
          num_bets=('bet_id', 'count'),
          num_wins=('is_win', 'sum'),
          total_stake=('stake', 'sum'),
          total_ggr=('GGR', 'sum'),
          avg_odds=('odds', 'mean')
      )
      .reset_index()
)

by_type['win_rate'] = by_type['num_wins'] / by_type['num_bets']
by_type['profit'] = -by_type['total_ggr']
by_type['roi'] = by_type['profit'] / by_type['total_stake']

for col in ['total_stake', 'total_ggr', 'avg_odds', 'win_rate', 'profit', 'roi']:
    by_type[col] = by_type[col].round(3)

by_type_json = by_type.to_dict(orient='records')

result = {
    "sports_by_profit": sports_by_profit,
    "sports_by_volume": sports_by_volume,
    "by_type": by_type_json
}

with open("stats.json", "w") as f:
    json.dump(result, f, indent=2)
