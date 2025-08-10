<?php
require_once "api/src/routes/Router.php";

require_once "api/src/controllers/AutorControl.php";
require_once "api/src/middlewares/AutorMiddleware.php";

require_once "api/src/controllers/LivroControl.php";
require_once "api/src/middlewares/LivroMiddleware.php";

require_once "api/src/controllers/GeneroControl.php";
require_once "api/src/middlewares/GeneroMiddleware.php";

class Roteador
{
public function __construct(private Router $router = new Router())
    {
        // Essa instância será usada para registrar e tratar rotas da aplicação
        $this->router = new Router();

        // Por exemplo: permitir requisições de diferentes domínios, definir tipo de conteúdo etc.
        $this->setupHeaders();

        // Aqui são mapeadas URNs específicas para controladores e métodos que irão tratá-las
        $this->setupAutorRoutes();
        $this->setupGenerosRoutes();
        $this->setupLivrosRoutes();
        $this->setupBackupRoutes();
        $this->setup404Route();

    }
    private function setup404Route(): void
    {
        $this->router->set404(function (): void {
            header('Content-Type: application/json');
            (new Response(
                success: false,
                message: "Rota não encontrada",
                error: [
                    'code' => 'routing_error',  
                    'message' => 'Rota não mapeada' 
                ],
                httpCode: 404  
            ))->send();

        });
    }
    /**
     * Configura os cabeçalhos HTTP necessários para a aplicação.
     * Os cabeçalhos configurados incluem:
     * - Métodos permitidos (GET, POST, PUT, DELETE)
     * - Origem permitida (qualquer origem, usando "*")
     * - Cabeçalhos permitidos nas requisições (Content-Type, Authorization)
     */
    private function setupHeaders(): void
    {
        header(header: 'Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        header(header: 'Access-Control-Allow-Origin: *');

        header(header: 'Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    private function sendErrorResponse(Throwable $throwable, string $message): never
    {
        // Registra a exceção no arquivo de log usando a classe Logger.
        // Isso ajuda a manter um histórico dos erros que ocorreram na aplicação.
        Logger::Log(throwable: $throwable);


        (new Response(
            success: false,
            message: $message,
            error: [
                'code' => $throwable->getCode(),  
                'message' => $throwable->getMessage() 
            ],
            httpCode: 500  
        ))->send();

        exit();
    }
private function setupAutorRoutes(): void
{
    $this->router->get(pattern: '/autores', fn: function (): never
        {

            try {
                $autorControl = new AutorControl();

                if ((isset($_GET['page'])) && isset($_GET['limit'])) {
                    $page = $_GET['page'];   
                    $limit = $_GET['limit']; 

                    // Se os parâmetros de paginação foram fornecidos, valida-os com o middleware
                    (new AutorMiddleware())
                        ->isValidPage(page: $page) 
                        ->isValidLimit(limit: $limit); 

                    // Chama o método do controlador para listar os autores com paginação
                    $autorControl->listPaginated(page: $page, limit: $limit);
                } else {
                    $autorControl->index();
                }
            } catch (Throwable $throwable) {
                // Caso ocorra um erro durante o processamento, envia uma resposta de erro para o cliente
                $this->sendErrorResponse(
                    throwable: $throwable,
                    message: 'Erro na seleção de dados'
                );
            }

            //(não continua a execução do código)
            exit();
        });

    $this->router->get(pattern: "/autores/(\d+)", fn: function ($idAutor): never {
            try {
                $autorMiddleware = new AutorMiddleware();
                $autorMiddleware
                    ->isValidId(idAutor: $idAutor); 

                $autorControl = new AutorControl();
                $autorControl->show(idAutor: $idAutor); 

            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na seleção de dados' 
                );
            }
            exit();
        });
        
        // Rota para criar um novo autor POST /autores
        $this->router->post(pattern: "/autores", fn: function (): never {
            try {
                
                $requestBody = file_get_contents(filename: "php://input");
                $autorMiddleware = new AutorMiddleware();
                $stdAutor = $autorMiddleware->stringJsonToStdClass(requestBody: $requestBody);
                $autorMiddleware
                    ->isValidNomeAutor(nomeAutor: $stdAutor->autor->nomeAutor) 
                    ->hasNotAutorByName(nomeAutor: $stdAutor->autor->nomeAutor)
                    ->isValidNacionalidade(nacionalidade: $stdAutor->autor->nacionalidade);
                $autorControl = new AutorControl();
                $autorControl->store(stdAutor: $stdAutor); 
        
                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro ao inserir um novo autor' 
                );
                exit();
            }
        });
        // Rota para atualizar um autor existente
        // Endpoint de exemplo: PUT /autores/123
        $this->router->put(pattern: "/autores/(\d+)", fn: function ($id): never {
            try {
                $requestBody = file_get_contents(filename: "php://input");
                $autorMiddleware = new AutorMiddleware();
                $stdAutor = $autorMiddleware->stringJsonToStdClass(requestBody: $requestBody);
                $autorMiddleware
                    ->isValidId(idAutor: $id) 
                    ->isValidNomeAutor(nomeAutor: $stdAutor->autor->nomeAutor) 
                    ->hasNotAutorByName(nomeAutor: $stdAutor->autor->nomeAutor)
                    ->isValidNacionalidade(nacionalidade: $stdAutor->autor->nacionalidade);
                $stdAutor->autor->idAutor = $id;
                $autorControl = new AutorControl();
                $autorControl->edit(stdAutor: $stdAutor); 

                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na atualização dos dados' 
                );
                exit();
            }
        });
        // Rota para excluir um autor específico pelo ID
        // Endpoint de exemplo: DELETE /autores/123
        $this->router->delete(pattern: "/autores/(\d+)", fn: function ($idAutor): never {
            try {
    
                $autorMiddleware = new AutorMiddleware();
                $autorMiddleware->isValidId(idAutor: $idAutor);
                $autorControl = new AutorControl();
                $autorControl->destroy(idAutor: $idAutor); 

                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na exclusão de dados' 
                );
                exit();
            }
        });
        
    }
    private function setupGenerosRoutes():void{

        $this->router->get(pattern: '/generos', fn: function (): never
        {

            try {
                $generoControl = new GeneroControl();

                if ((isset($_GET['page'])) && isset($_GET['limit'])) {
                    $page = $_GET['page'];   
                    $limit = $_GET['limit']; 

                    // Se os parâmetros de paginação foram fornecidos, valida-os com o middleware
                    (new GeneroMiddleware())
                        ->isValidPage(page: $page) 
                        ->isValidLimit(limit: $limit); 

                    // Chama o método do controlador para listar os autores com paginação
                    $generoControl->listPaginated(page: $page, limit: $limit);
                } else {
                    $generoControl->index();
                }
            } catch (Throwable $throwable) {
                // Caso ocorra um erro durante o processamento, envia uma resposta de erro para o cliente
                $this->sendErrorResponse(
                    throwable: $throwable,
                    message: 'Erro na seleção de dados'
                );
            }

            //(não continua a execução do código)
            exit();
        });
        $this->router->get(pattern: "/generos/(\d+)", fn: function ($idGenero): never {
            try {
                $generoMiddleware = new GeneroMiddleware();
                $generoMiddleware
                    ->isValidId(idGenero: $idGenero); 

                $generoControl = new GeneroControl();
                $generoControl->show(idGenero: $idGenero); 

            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na seleção de dados' 
                );
            }
            exit();
        });
        // Rota para criar um novo caro POST /generos
        $this->router->post(pattern: "/generos", fn: function (): never {
            try {
                
                $requestBody = file_get_contents(filename: "php://input");
                $generoMiddleware = new GeneroMiddleware();
                $stdGenero = $generoMiddleware->stringJsonToStdClass(requestBody: $requestBody);
                $generoMiddleware
                    ->isValidNomeGenero(nomeGenero: $stdGenero->genero->nomeGenero) 
                    ->hasNotGeneroByName(nomeGenero: $stdGenero->genero->nomeGenero);
                $generoControl = new GeneroControl();
                $generoControl->store(stdGenero: $stdGenero); 
        
                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro ao inserir um novo autor' 
                );
                exit();
            }
        });
        // Rota para atualizar um autor existente
        // Endpoint de exemplo: PUT /generos/123
        $this->router->put(pattern: "/generos/(\d+)", fn: function ($idGenero): never {
            try {
                $requestBody = file_get_contents(filename: "php://input");
                $generoMiddleware = new GeneroMiddleware();
                $stdGenero = $generoMiddleware->stringJsonToStdClass(requestBody: $requestBody);
                $generoMiddleware
                    ->isValidId(idGenero: $idGenero) 
                    ->isValidNomeGenero(nomeGenero: $stdGenero->genero->nomeGenero) 
                    ->hasNotGeneroByName(nomeGenero: $stdGenero->genero->nomeGenero);
                $stdGenero->genero->idGenero = $idGenero;
                #$stdAutor->autor->idAutor = $id;
                $generoControl = new GeneroControl();
                $generoControl->edit(stdGenero: $stdGenero); 

                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na atualização dos dados' 
                );
                exit();
            }
        });
        // Rota para excluir um autor específico pelo ID
        // Endpoint de exemplo: DELETE /generos/123
        $this->router->delete(pattern: "/generos/(\d+)", fn: function ($idGenero): never {
            try {
    
                $generoMiddleware = new GeneroMiddleware();
                $generoMiddleware->isValidId(idGenero: $idGenero);
                $generoControl = new GeneroControl();
                $generoControl->destroy(idGenero: $idGenero); 

                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na exclusão de dados' 
                );
                exit();
            }
        });

    }
    private function setupLivrosRoutes(): void{
        //faz o GET com /livros
        $this->router->get(pattern: '/livros', fn: function (): never
        {
            try {
                $livroControl = new LivroControl();

                if ((isset($_GET['page'])) && isset($_GET['limit'])) {
                    $page = $_GET['page'];   
                    $limit = $_GET['limit']; 
                    (new LivroMiddleware())
                        ->isValidPage(page: $page) 
                        ->isValidLimit(limit: $limit); 
                    $livroControl->listPaginated(page: $page, limit: $limit);
                } else {
                    $livroControl->index();
                }
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable,
                    message: 'Erro na seleção de dados'
                );
            }
            exit();
        });
        $this->router->get(pattern: "/livros/(\d+)", fn: function ($idLivro): never {
            try {
                $livroMiddleware = new LivroMiddleware();
                $livroMiddleware
                    ->isValidId(idLivro: $idLivro); 

                $livroControl = new LivroControl();
                $livroControl->show(idLivro: $idLivro); 

            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na seleção de dados' 
                );
            }
            exit();
        });
        // Rota para criar um novo caro POST /livros
        $this->router->post(pattern: "/livros", fn: function (): never {
            try {
                
                $requestBody = file_get_contents("php://input");
                $livroMiddleware = new LivroMiddleware();
                $stdLivro = $livroMiddleware->stringJsonToStdClass(requestBody: $requestBody);

                $livroMiddleware
                    ->isValidNomeLivro(nomeLivro: $stdLivro->livro->nomeLivro) 
                    ->hasNotLivroByName(nomeLivro: $stdLivro->livro->nomeLivro)
                    ->isValidEditora(editora: $stdLivro->livro->editora)
                    ->isValidAnoPublicacao(anoPublicacao: $stdLivro->livro->anoPublicacao);

                $autorMiddleware = new AutorMiddleware();
                $autorMiddleware
                    ->isValidId(idAutor: $stdLivro->livro->autor->idAutor)
                    ->hasAutorById(idAutor: $stdLivro->livro->autor->idAutor);

                $generoMiddleware = new GeneroMiddleware();
                $generoMiddleware
                    ->isValidId(idGenero: $stdLivro->livro->genero->idGenero)
                    ->hasGeneroById(idGenero: $stdLivro->livro->genero->idGenero);
                
                $livroControl = new LivroControl();
                $livroControl->store(stdLivro: $stdLivro); 
                //echo "recebeu o texto json:  $requestBody";
                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro ao inserir um novo livro' 
                );
                exit();
            }
        });
        // Rota para atualizar um autor existente
        // Endpoint de exemplo: PUT /livros/123
        $this->router->put(pattern: "/livros/(\d+)", fn: function ($idLivro): never {
            try {
                $requestBody = file_get_contents(filename: "php://input");
                $livroMiddleware = new LivroMiddleware();
                $stdLivro = $livroMiddleware->stringJsonToStdClass(requestBody: $requestBody);
                $livroMiddleware
                    ->isValidId(idLivro: $idLivro) // Valida o ID do cargo
                    ->isValidNomeLivro(nomeLivro: $stdLivro->livro->nomeLivro) 
                    ->hasNotLivroByName(nomeLivro: $stdLivro->livro->nomeLivro);
                $stdLivro->livro->idLivro = $idLivro;
                #$stdAutor->autor->idAutor = $id;
                $livroControl = new LivroCOntrol();
                $livroControl->edit(stdLivro: $stdLivro); 

                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na atualização dos dados' 
                );
                exit();
            }
        });
        // Rota para excluir um autor específico pelo ID
        // Endpoint de exemplo: DELETE /livros/123
        $this->router->delete(pattern: "/livros/(\d+)", fn: function ($idLivro): never {
            try {
    
                $livroMiddleware = new LivroMiddleware();
                $livroMiddleware->isValidId(idLivro: $idLivro);
                $livroControl = new LivroControl();
                $livroControl->destroy(idLivro: $idLivro); 

                exit();
            } catch (Throwable $throwable) {
                $this->sendErrorResponse(
                    throwable: $throwable, 
                    message: 'Erro na exclusão de dados' 
                );
                exit();
            }
        });

    }



    private function setupBackupRoutes(): void
    {
        // Rota para listar todos os cargos ou realizar paginação
        // Endpoint de exemplo: GET /cargos?page=1&limit=10
        $this->router->get(pattern: '/backup', fn: function (): never {
            try {
                require_once "api/src/db/Database.php";
                Database::backup();
            } catch (Throwable $throwable) {
                // Caso ocorra um erro, chama a função para enviar uma resposta de erro ao cliente
                $this->sendErrorResponse(throwable: $throwable, message: 'Erro na seleção de dados');
            }
            // Finaliza a execução do script após a resposta ser enviada
            exit();
        });
    }
    

    public function start(): void
    {
        $this->router->run();
    }
}
