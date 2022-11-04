#install pip : mysql-connector-python python-dotenv tz python-dateutil
import requests
import action_repository
from dateutil.parser import parse
import pytz
from dotenv import load_dotenv


def request_action_populaire(ga_config):
    group_id, user_id, ga_id = ga_config

    def remove_date_prefix(date_str):
        date = parse(date_str)
        return str(date.replace( tzinfo=pytz.utc ).replace(tzinfo=None))

    def parse_json_response(record):
        return (
            group_id,
            user_id,
            record["name"],
            record["name"],
            remove_date_prefix(record["startTime"]),
            remove_date_prefix(record["endTime"]),
            record["location"]["shortAddress"],
            record["location"]["coordinates"]["coordinates"][1],
            record["location"]["coordinates"]["coordinates"][0],
            record["id"],
        )
    response = requests.get("https://actionpopulaire.fr/api/groupes/" + ga_id + "/evenements/a-venir/")
    return map(parse_json_response, response.json())


load_dotenv()

for ga_config in action_repository.get_ga_config_to_sync():
    for action_from_action_populaire in request_action_populaire(ga_config):
        action = action_repository.get_action_by_external_ref(action_from_action_populaire[9])
        if action == None:
            action_repository.insert_action(action_from_action_populaire)
        elif action == action_from_action_populaire:
            continue
        else:
            action_repository.update_action(action_from_action_populaire)



