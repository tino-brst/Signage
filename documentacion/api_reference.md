# API - Referencia



__Tabla de contenidos__

[TOC]

##### Punto de acceso a la API 

`server_url` + `/index.php/api/` + `screens|groups|playlists|images`

##### Codigos de respuesta

Siguiendo convenciones establecidas para códigos de respuesta HTTP, a continuación se detallan los usados por la API:

| Código | Significado                                                  |
| ------ | ------------------------------------------------------------ |
| 200    | Request resuelto con exito                                   |
| 201    | Recurso creado                                               |
| 204    | Request resuelto con exito sin contenido en la respuesta     |
| 400    | Formato de request no valido (faltan parametros, etc)        |
| 404    | Recurso no encontrado                                        |
| 500    | Error interno en el servidor (errores de base de datos, etc) |

## Pantallas

##### Obtener - GET
```json
query parameters
	· id / udid (int)   // obtengo info de pantalla a partir de
						// su id de directorio o udid de dispositivo
response - 200
	{
		"id": "71",
		"name": "Pantalla 1",
		"type": "screen",
		"parent_id": "0",
		"date_created": "2018-02-15 05:16:32",
		"udid": "fskjhs13dhk12",
		"playlist_id": "1"
	}
```

##### Crear - PUT
```json
request
	{
		"parentId": 12,
		"name": "Pantalla 1",
		"extraFields": {
			// columnas especificas a las pantallas
			... 
		}
	}
response - 201
	{
		"id": "71",
		"name": "Pantalla 1",
		"type": "screen",
		"parent_id": "12",
		"date_created": "2018-02-15 05:16:32",
		"udid": "fskjhs13dhk12",
		"playlist_id": "1"
		// extraField 1, extraField 2, ...
	}
```

##### Modificar - POST
```json
request
	{
		"id": 71,
		"name": "Nuevo nombre", // excepto el id, todos los campos son opcionales
		"extraFields": {
			"playlist_id": 2
			// columnas especificas a las pantallas
			... 
		}
	}
response - 200
	{
		"id": "71",
		"name": "Nuevo nombre",
		"type": "screen",
		"parent_id": "13",
		"date_created": "2018-02-15 05:16:32",
		"udid": "fskjhs13dhk12",
		"playlist_id": "2"
		// extraField 1, extraField 2, ...
	}
```

##### Eliminar - DELETE
```json
query parameters
	· id (int)
response - 204
```



## Grupos

##### Obtener - GET
```json
query parameters
	· id (int) 				// dejando id: "" (campo vacio) retorna el grupo raiz
	· includePath (bool) 	// opcional: incluye el path del grupo
	· includeContent (bool) // opcional: incluye contenido del grupo
response - 200
	{
		"id": "1",
		"name": "Group",
		"type": "group",
		"parent_id": "0",
		"date_created": "2018-02-14 08:29:42",
		"path": [
			{
				"id": "1",
				"name": "Group"
			},
			...
		],
		"content": [
			{
				"id": "12",
				"name": "Child group",
				"type": "group",
				"parent_id": "1",
				"date_created": "2018-02-14 10:37:48"
			},
			...
		]
	}
```

##### Crear - PUT
```json
request
	{
		"parentId": 12,
		"name": "Hogar",
		"extraFields": {
			// columnas especificas a los grupos
			... 
		}
	}
response - 201
	{
		"id": "17",
		"name": "Hogar",
		"type": "group",
		"parent_id": "12",
		"date_created": "2018-02-14 11:37:31"
		// extraField 1, extraField 2, ...
	}
```

##### Modificar - POST
```json
request
	{
		"id": 23,
		"name": "Nuevo nombre", // excepto el id, todos los campos son opcionales
		"extraFields": {
			// columnas especificas a los grupos
			... 
		}
	}
response - 200
	{
		"id": "23",
		"name": "Nuevo nombre",
		"type": "group",
		"parent_id": "12",
		"date_created": "2018-02-14 11:37:31"
		// extraField 1, extraField 2, ...
	}
```

##### Eliminar - DELETE
```json
query parameters
	· id (int)
response - 204
```



## Playlists

##### Obtener - GET
```json
query parameters
	· id (int) 			  // opcional: una playlist / todas
	· includeItems (bool) // opcional: incluye items de la/s playlists
response - 200
	[
		{
			"id": "1",
			"name": "cities",
			"items": [
				{
					"id": "1",
					"location": "public\/images\/image1.jpg"
				},
				...
			]
		},
		...
	]
```

##### Crear - PUT
```json
request
	{
		"name": "hogar"
	}
response - 201
	{
		"id": "32",
		"name": "hogar"
		"items": []
	}
```

##### Modificar - POST
```json
request
	{
		"id": "32",
		"name": "cajas", // excepto el id, todos los campos son opcionales
		"items": [
			{
				"id": 5
			},
			{
				"id": 6
			},
			...
		]
	}
response - 200
	{
		"id": "32",
		"name": "cajas",
		"items": [
			{
				"id": "5",
				"location": "public\/images\/image5.jpg"
			},
			...
		]
	}
```

##### Eliminar - DELETE
```json
query parameters
	· id (int)
response - 204
```
