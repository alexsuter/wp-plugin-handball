CREATE TABLE hcg_team (
	team_id INT primary key,
	team_name VARCHAR(255) NOT NULL,
	saison VARCHAR(4) NOT NULL
);

CREATE TABLE hcg_match (
	game_id INT primary key,
	game_nr INT NOT NULL,
	team_a_name VARCHAR(255) NULL,
	team_b_name VARCHAR(255) NULL
);

ALTER TABLE hcg_match ADD COLUMN game_datetime DATETIME;
ALTER TABLE hcg_match ADD COLUMN game_type_long VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN game_type_short VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN league_long VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN league_short VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN round VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN game_status VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN team_a_score_ht INTEGER;
ALTER TABLE hcg_match ADD COLUMN team_b_score_ht INTEGER;
ALTER TABLE hcg_match ADD COLUMN team_a_score_ft INTEGER;
ALTER TABLE hcg_match ADD COLUMN team_b_score_ft INTEGER;
ALTER TABLE hcg_match ADD COLUMN venue VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN venue_address VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN venue_zip VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN venue_city VARCHAR(255);
ALTER TABLE hcg_match ADD COLUMN spectators INTEGER;
ALTER TABLE hcg_match ADD COLUMN round_nr INTEGER;
ALTER TABLE hcg_match ADD COLUMN fk_team_id INTEGER NOT NULL;
ALTER TABLE hcg_match ADD FOREIGN KEY (fk_team_id) REFERENCES hcg_team(team_id);
