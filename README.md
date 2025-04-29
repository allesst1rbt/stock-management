# Stock-managemente
Apenas testando
## observaçôes:
### 1-Tenha o docker instalado, se não tiver e caso queira, siga os passos abaixo
# Ultilizando com o docker:
## Instalando o docker:
### Windows:
#### 1-Primeiro instale o wsl na sua maquina https://docs.microsoft.com/pt-br/windows/wsl/install-win10
#### 2-Segundo  instale o docker na sua maquina https://docs.docker.com/engine/install/
### Unbutu:
#### 1-https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04-pt
### Manjaro:
#### 1-https://linuxconfig.org/manjaro-linux-docker-installation
## Ultilizando a aplicação:
Clone a aplicação
````
git clone https://github.com/allesst1rbt/stock-management.git
````
Adentre a pasta da aplicação 
````
cd stock-management
````
Suba o docker 
````
docker-compose up -d
````
## Realizando os testes:
Entre no container :
```
docker exec -it stock-management-app-1 bash
```
Rode os seeds(as vezes nao roda automaticamente):
```
php artisan db:seed
```
Execute os testes : 
```
php artisan test
```
Documentação esta na url : 
```
http://srv795511.hstgr.cloud:8080/docs/api#/
```


