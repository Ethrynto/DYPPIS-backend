# Platforms API

### Get platforms
`[GET] /api/v1/platform-types/{id}/platforms` - Get all platforms by the platform type ID. Query params:
- `perPage: int` _(optional)_
- `page: int` _(optional)_



<details>
  <summary>Example: </summary>

```json
{
    "data": [
        {
            "id": "1a8532ec-734a-4cab-bb52-9bc8bb2b4af8",
            "slug": "microsoft",
            "title": "Microsoft",
            "image": {
                "id": "16236c29-d7da-56f9-88df-40dfde284a26",
                "file_name": "microsoft-logo.svg",
                "file_type": "image/svg",
                "file_size": 2096,
                "category": {
                    "url": "/storage/uploads/images/platforms"
                }
            },
            "banner": null
        },
        {
            "id": "2a8532ec-734a-4cab-bb52-9bc8bb2b4af8",
            "slug": "google",
            "title": "Google",
            "image": {
                "id": "26236c29-d7da-56f9-88df-40dfde284a26",
                "file_name": "google-logo.svg",
                "file_type": "image/svg",
                "file_size": 2096,
                "category": {
                    "url": "/storage/uploads/images/platforms"
                }
            },
            "banner": null
        },
        {
            "id": "7a8532ec-734a-4cab-bb52-9bc8bb2b4af8",
            "slug": "jetbrains",
            "title": "Jetbrains",
            "image": {
                "id": "76236c29-d7da-56f9-88df-40dfde284a26",
                "file_name": "jetbrains-logo.svg",
                "file_type": "image/svg",
                "file_size": 2096,
                "category": {
                    "url": "/storage/uploads/images/platforms"
                }
            },
            "banner": null
        },
        {
            "id": "8a8532ec-734a-4cab-bb52-9bc8bb2b4af8",
            "slug": "adobe",
            "title": "Adobe",
            "image": {
                "id": "86236c29-d7da-56f9-88df-40dfde284a26",
                "file_name": "adobe-logo.svg",
                "file_type": "image/svg",
                "file_size": 2096,
                "category": {
                    "url": "/storage/uploads/images/platforms"
                }
            },
            "banner": null
        }
    ],
    "pagination": {
        "total": 4,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1,
        "from": 1,
        "to": 4
    },
    "links": {
        "first": "http://127.0.0.1:7777/api/v1/platform-types/software/platforms?page=1",
        "last": "http://127.0.0.1:7777/api/v1/platform-types/software/platforms?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:7777/api/v1/platform-types/software/platforms?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:7777/api/v1/platform-types/software/platforms",
        "per_page": 10,
        "to": 4,
        "total": 4
    }
}
```
</details>


### Get platform by ID
`[GET] /api/v1/platforms/{id}` - Get platform by ID.

<details>
  <summary>Example</summary>

```json
{
    "data": {
        "id": "1a8532ec-734a-4cab-bb52-9bc8bb2b4af8",
        "slug": "microsoft",
        "title": "Microsoft",
        "image": {
            "id": "16236c29-d7da-56f9-88df-40dfde284a26",
            "file_name": "microsoft-logo.svg",
            "file_type": "image/svg",
            "file_size": 2096,
            "category": {
                "url": "/storage/uploads/images/platforms"
            }
        },
        "banner": null,
        "type": {
            "id": "2734e074-be9c-315f-a1df-31837290482a",
            "slug": "software",
            "title": {
                "de": "Software",
                "en": "Software",
                "es": "Software",
                "fr": "Logiciel",
                "it": "Software",
                "ru": "Программное обеспечение"
            },
            "image": {
                "id": "26236c29-d7da-41e9-97df-40ffde284a93",
                "file_name": "software-icon.svg",
                "file_type": "image/svg",
                "file_size": 2096,
                "category": {
                    "url": "/storage/uploads/images/icons"
                }
            }
        },
        "categories": []
    }
}
```
</details>

### Add platform by ID
`[POST] /api/v1/platforms` - Add platform. Requirements:
- `slug: string` - slug. _(Can be a unique)_
- `title: string` - platform name
- `type_id: string` - type id for platform _(Can be a UUID)_
- `image: file` - platform icon
- `banner: file` - platform banner _(optional)_

<details>
  <summary>Example</summary>

```json
{
    "data": {
        "id": "6ce2b1cc-0a01-4c45-9d5d-402a795d942c",
        "slug": "test",
        "title": "Test Game",
        "image": {
            "id": "22add423-f351-434c-bb0d-61cbda45ff7f",
            "file_name": "1fb01e21-fceb-4884-9b82-a8f0d53b6ffb.png",
            "file_type": "image/png",
            "file_size": 1335538,
            "category": {
                "url": "/storage/uploads/images/platforms"
            }
        },
        "banner": {
            "id": "a1693938-db43-4c1f-b5c0-98240fb5c4de",
            "file_name": "f9cd0906-80d2-485c-a5c6-5630321f4939.png",
            "file_type": "image/png",
            "file_size": 1335538,
            "category": {
                "url": "/storage/uploads/images/banners"
            }
        },
        "type": {
            "id": "1734e074-be9c-315f-a1df-31837290482a",
            "slug": "games",
            "title": {
                "de": "Spiele",
                "en": "Games",
                "es": "Juegos",
                "fr": "Jeux",
                "it": "Giochi",
                "ru": "Игры"
            },
            "image": {
                "id": "16236c29-d7da-41e9-97df-40ffde284a93",
                "file_name": "games-icon.svg",
                "file_type": "image/svg",
                "file_size": 2096,
                "category": {
                    "url": "/storage/uploads/images/icons"
                }
            }
        },
        "categories": []
    }
}
```
</details>

### Delete platform by ID
`[DELETE] /api/v1/platforms/{id}` - delete platform by ID.

<details>
  <summary>Example</summary>

```json
{
    "status": "success",
    "message": "Resource deleted successfully."
}
```
</details>
