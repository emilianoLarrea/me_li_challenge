define({ "api": [
  {
    "type": "post",
    "url": "/api/mail",
    "title": "Buscar y guardar mails",
    "name": "Buscar_y_guardar_mails",
    "group": "Mail",
    "description": "<p>Se encarga de buscar mails en la cuenta de Gmail especificada. Los correos que coinciden con el criterio de búsqueda, son encolados para ser guardados en la base de datos.</p>",
    "examples": [
      {
        "title": "Body",
        "content": "{\n   \"email\": \"Mail de Google a utilizar.\",\n   \"password\": \"Contraseña de acceso al mail.\",\n   \"search_folder\": \"Carpeta de búsqueda de Mails. Si se deja vacío por defecto es INBOX.\",\n   \"search_criteria\": \"Criterio de búsqueda de Mails. Es la cadena de caracteres que se busca que esté presente en el Asunto o Cuerpo del mensaje. Si se deja vacío por defecto es DevOps\"\n}",
        "type": "json"
      }
    ],
    "version": "0.0.0",
    "filename": "app/Services/Mail/Utility/routes.php",
    "groupTitle": "Mail",
    "success": {
      "examples": [
        {
          "title": "Respuesta",
          "content": "  HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"data\": {\n      \"Getting and saving {mails_quantity} mails.\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "400 Bad Request",
          "content": "HTTP/1.1 400 Bad Request\n[\n     {\n        \"success\": false,\n        \"data\":{\n             \"path\" : \"{Nombre de la propiedad}\",\n             \"message\" : \"{Motivo del error}\"\n         }\n     },\n     ...\n]",
          "type": "json"
        },
        {
          "title": "500 Server Error",
          "content": "HTTP/1.1 500 Internal Server Error\n{\n   \"error\" : \"Not Found\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/api/mail",
    "title": "Obtener Mail",
    "name": "Obtener_Mail",
    "group": "Mail",
    "description": "<p>Se encarga de obtener información de las entidades Mail.</p>",
    "version": "0.0.0",
    "filename": "app/Services/Mail/Utility/routes.php",
    "groupTitle": "Mail",
    "success": {
      "examples": [
        {
          "title": "Respuesta",
          "content": "  HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"data\": [\n        {\n           \"id\": \"id de objeto Mail.\",\n           \"uid\": \"Identificador externo del objeto Mail.\",\n           \"fecha\": \"Fecha correspondiente al header 'date' del mail. Representa la fecha en la que se recibió el correo.\",\n           \"from\": \"Corresponde al header 'from' del mail. Representa el origen del correo.\",\n           \"subject\": \"Corresponde al header 'subject' del mail. Representa el asunto del correo.\",\n           \"updated_at\": \"Fecha de modificación del objeto Mail\",\n           \"created_at\": \"Fecha de creación del objeto Mail\",\n           \"deleted_at\": \"Fecha de eliminación del objeto Mail\"\n        },\n        ...\n    ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "400 Bad Request",
          "content": "HTTP/1.1 400 Bad Request\n[\n     {\n        \"success\": false,\n        \"data\":{\n             \"path\" : \"{Nombre de la propiedad}\",\n             \"message\" : \"{Motivo del error}\"\n         }\n     },\n     ...\n]",
          "type": "json"
        },
        {
          "title": "500 Server Error",
          "content": "HTTP/1.1 500 Internal Server Error\n{\n   \"error\" : \"Not Found\"\n}",
          "type": "json"
        }
      ]
    }
  }
] });
