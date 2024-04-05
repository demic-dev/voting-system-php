import requests # type: ignore
import json

BASE_URL = "http://localhost:9000/api/index.php"

### ADMIN

admin_request = requests.post(BASE_URL, json={
    "API_NAME": "sign-up",
    "name": "Michele",
    "surname": "De Cillis",
    "email": "admin@admin.com",
    "password": "12345678"
})

print(admin_request.status_code)

admin_login = requests.post(BASE_URL, json={
    "API_NAME": "sign-in",
    "email": "admin@admin.com",
    "password": "12345678"
})

### USERS

bot_number = 25
bots = []
for n in range(bot_number):
    bot_request = requests.post(BASE_URL, json={
        "API_NAME": "sign-up",
        "name": "Bot",
        "surname": f"{n}",
        "email": f"bot-{n}@user.com",
        "password": "12345678"
    })
    res = json.loads(bot_request.text)
    res = json.loads(res["data"])
    bots.append(res["id"])
    print(admin_request.status_code)
    
# ### USERLISTS

userlist_number = 4
userlists = []
for n in range(userlist_number):
    userlist_request = requests.post(BASE_URL, json={
        "API_NAME": "new-userlist",
        "name": f"Userlist n. {n}",
        "users": bots[n:(n+1)*userlist_number],
        "proxies": []
    })
    res = json.loads(bot_request.text)
    res = json.loads(res["data"])
    userlists.append(res["id"])
    print(userlist_request.status_code)

    