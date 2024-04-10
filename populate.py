import requests # type: ignore
import json

BASE_URL = "http://localhost:9000/api/index.php"

### ADMIN

admin_request = requests.post(BASE_URL, json={
    "API_NAME": "sign-up",
    "name": "Admin",
    "surname": "Admin",
    "email": "admin@admin.com",
    "password": "12345678"
})

print(admin_request.status_code)

admin_login = requests.post(BASE_URL, json={
    "API_NAME": "sign-in",
    "email": "admin@admin.com",
    "password": "12345678"
})

cookies = admin_login.cookies.get_dict()

print(cookies)

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
