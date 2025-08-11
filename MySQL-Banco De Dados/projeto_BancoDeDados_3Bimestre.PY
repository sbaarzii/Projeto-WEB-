import mysql.connector
from prettytable import PrettyTable
from mysql.connector import Error
def abreBanco():
    try:
        global conexao
        conexao=mysql.connector.connect(host='localhost', database='acessibilidade',user='root',password='')
        if conexao.is_connected():
            global comandosql
            comandosql=conexao.cursor()
            comandosql.execute('select database();')
            nomebanco = comandosql.fetchone()
            print(f'Banco de dados acessado = {nomebanco}')
            print('='*80)
            return 1
        else:
            print('Conexão não realizada com banco')
            return 0
    except Exception as erro:
        print(f'Ocorreu erro: {erro}')

def cadastrarLocal():
    try:
            comandosql=conexao.cursor()
            nomeLocal= (input("Digite o nome do local: "))
            EnderecoLocal=(input("Digite o endereço do local: "))
            comandosql.execute(f'insert into locais(nomeLocal,EnderecoLocal) values ("{nomeLocal}", "{EnderecoLocal}");')
            conexao.commit()
            print("Cadastro efetuado")
    except Exception as erro:
        print(f'Ocorreu erro: {erro}')

def mostrarTodos():
    # criando duas colunas para o grid que exibirá todas as diciplinas cadastradas
 grid = PrettyTable(['Id Locais', "Nomes dos Locais", "Endereço"])
 try:
    comandosql = conexao.cursor()
    # repare que NÃO USEI A CLÁUSULA WHERE, ou seja, todas os locais gravados serão consultados
    comandosql.execute(f'select * from locais;')
    # O MÉTODO fetchall() retornará todos os registros filtrados (um ou mais registros) pelo comando select
    tabela = comandosql.fetchall()
    # O método rowcount conta quantos registros foram filtrados, caso tenha registroiltrado entra no if
    if comandosql.rowcount > 0:
    # se existir pelo menos um local na tabela temporária, mostre-as no grid
        for registro in tabela:
        # criando as linhas do grid com os registros lidos da tabela temporária. Mostrando todos os locais
            grid.add_row([registro[0],registro[1],registro[2]])
        print(grid)
    else:
     print('Não existem locais cadastradas!!!')
 except Exception as erro:
    print(f'Ocorreu erro: {erro}')

def consultarLocais():
    try:
        comandosql = conexao.cursor()
        comandosql.execute(f'select * from locais where idLocal = {idLocal};')
        tabela = comandosql.fetchall()
        # verivificando quanto registros de disciplinas de código igual ao digitado
        # filtrados pelo select foram guardados na tabela temporária
        if comandosql.rowcount > 0:
            # se existir pelo menos uma disciplina na tabela temporária, mostre os dadosda coluna 1
            for registro in tabela:
                print(f'Nome do Local: {registro[1]}')
                return 'cadastrado'
        else:
            return 'naoCadastrado'
    except Exception as error:
        return (f'Ocorreu erro ao tentar consultar este local: Erro===>>> {error}')    



'''========================================= MÓDULO PRINCIPAL DO PROGRAMA==============================================='''

if abreBanco()==1:
  resp = input('Deseja entrar no módulo de locais? (1-Sim, ou qualquer tecla para sair) ==> ')
  if resp == '1':
        idLocal_input = input('Código do Local: (0 - Mostra Todos) ')
        if idLocal_input.isnumeric():
            idLocal = int(idLocal_input)

            if idLocal == 0:
                mostrarTodos()
                
                if consultarLocais(idLocal) == 'naoCadastrado':
                    nomeLocal = input('Nome do Local: ')
                    msg = cadastrarLocal(idLocal, nomeLocal)
                    print(msg)
                else:
                    op = input("Escolha: [A]-Alterar [E]-Excluir [C]-Cancelar Operações ==> ").upper()
                    while op not in ['A', 'E', 'C']:
                        op = input("ERRO! Escolha CORRETAMENTE: [A]-Alterar [E]-Excluir [C]-Cancelar ==> ").upper()
                
            
                