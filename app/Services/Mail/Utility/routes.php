<?php

// MailService Routes:

/** searchInMail()
  * 
  * @apiDefine searchInMail
  *
  * @apiSuccessExample {json} Respuesta
  *     HTTP/1.1 200 OK
  *   {
  *       "success": true,
  *       "data": {
  *         "Getting and saving {mails_quantity} mails."
  *       }
  *   }
  */
  /**
  * @api {post} /api/mail  Buscar y guardar mails
  * @apiName Buscar y guardar mails
  * @apiGroup Mail
  *
  * @apiDescription Se encarga de buscar mails en la cuenta de Gmail especificada. Los correos que coinciden con el criterio de búsqueda, son encolados para ser guardados en la base de datos. 
  *
  * @apiExample {json} Body
  *        {
  *           "email": "Mail de Google a utilizar.",
  *           "password": "Contraseña de acceso al mail.",
  *           "search_folder": "Carpeta de búsqueda de Mails. Si se deja vacío por defecto es INBOX.",
  *           "search_criteria": "Criterio de búsqueda de Mails. Es la cadena de caracteres que se busca que esté presente en el Asunto o Cuerpo del mensaje. Si se deja vacío por defecto es DevOps"
  *        }
  *
  * @apiUse searchInMail
  * @apiUse QueryValidationErrors
  * @apiUse OtherErrors
  *
 */

/**
  * @apiDefine getMails
  *
  * @apiSuccessExample {json} Respuesta
  *     HTTP/1.1 200 OK
  *   {
  *       "success": true,
  *       "data": [
  *           {
  *              "id": "id de objeto Mail.",
  *              "uid": "Identificador externo del objeto Mail.",
  *              "fecha": "Fecha correspondiente al header 'date' del mail. Representa la fecha en la que se recibió el correo.",
  *              "from": "Corresponde al header 'from' del mail. Representa el origen del correo.",
  *              "subject": "Corresponde al header 'subject' del mail. Representa el asunto del correo.",
  *              "updated_at": "Fecha de modificación del objeto Mail",
  *              "created_at": "Fecha de creación del objeto Mail",
  *              "deleted_at": "Fecha de eliminación del objeto Mail"
  *           },
  *           ...
  *       ]
  *   }
  */
  /**
  * @api {get} /api/mail  Obtener Mail
  * @apiName Obtener Mail
  * @apiGroup Mail
  *
  * @apiDescription Se encarga de obtener información de las entidades Mail.
  *
  * @apiUse getMails
  * @apiUse QueryValidationErrors
  * @apiUse OtherErrors
  *
 */ 
    
 /**
 * @apiDefine QueryValidationErrors
 *
 * @apiErrorExample 400 Bad Request
 *     HTTP/1.1 400 Bad Request
 *     [
 *          {
 *             "success": false,
 *             "data":{
 *                  "path" : "{Nombre de la propiedad}",
 *                  "message" : "{Motivo del error}"
 *              }
 *          },
 *          ...
 *     ]
 */
/**
 * @apiDefine OtherErrors
 *
 * @apiErrorExample 500 Server Error
 *     HTTP/1.1 500 Internal Server Error
 *     {
 *        "error" : "Not Found"
 *     }
 *
 */