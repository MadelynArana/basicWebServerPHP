<?php

    require_once __DIR__."/../model/AuthorModel.php";
    require_once __DIR__."/../model/core/response/Message.php";

    class AuthorController extends Message
    {
        public function getAuthor( $option )
        {
            if($_GET['action']=='author')
            {
                $database = new AuthorModel(); // Base de datos

                switch( $option )
                {
                    case 'get': // Válida que exista un id en el $_GET['id']. Si existe busca por ID de lo contrario muestra todos los registros.                  
                            if(isset($_GET['id'])) {
                                $response = $database->getData($_GET['id']);
                                ( $response != 0 ) ? $this->messageJson( $response ):  $this->messageJson("Record does not exist.");
                            }else{
                                $response = $database->getData(0);             
                                ( $response != 0 ) ? $this->messageJson( $response ):  $this->messageJson("There are no records.");
                            }
                        break;

                    case 'save': // Guarda o actualiza un nuevo registro.
                            // Se obtienen los datos enviados en el cuerpo de la petición.  
                            $jsonObj = json_decode( file_get_contents('php://input') ); 
                            $name      = $jsonObj->name;
                            $surname     = $jsonObj->surname;

                            if(isset($_GET['id'])){
                                $database->update($_GET['id'], $name , $surname ); // Actualiza un registro.                  
                                $this->response(200,"Success","Record was updated.");                         
                            }else{
                                $database->insert(0, $name , $surname ); // Guarda un nuevo registro.
                                $this->response(200,"Success","New record added.");
                            }
                        break;

                    case 'delete': // Elimina un registro de la base de datos. 
                            if(isset($_GET['id'])){
                                $database->delete($_GET['id']); 
                                $this->response(200,"Success","Record was deleted.");
                            }else{
                                $this->response(400,"error","Record was not deleted.");
                            }
                        break;
                        
                    default: 
                        $this->response(400,"error","default book non-existing element");  
                        break;
                }
            }
        }
    } // End class 
?>