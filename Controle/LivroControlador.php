<?php

include_once '../Modelo/LivroFisico.php';
include_once '../Modelo/LivroEletronico.php';
include_once '../Dao/LivroFisicoDao.php';
include_once '../Dao/LivroEletronicoDao.php';
    
class LivroControlador {
    
    public function salvaLivro($titulo, $autor, $genero, $edicao, $editora, $venda, $troca, $estado, $descricao, $caminhoLivroEletronico, $id_dono){
        try{
            if(empty($caminhoLivroEletronico)){
                $livro = new LivroFisico($titulo, $autor, $genero, $edicao, $editora, $descricao,$venda, $troca, $estado);
                $livroFisicoDao = LivroFisicoDao::getInstance();
                $retorno = $livroFisicoDao->salvaLivro($livro, $id_dono);
            }else{
                $livro = new LivroEletronico($titulo, $autor, $genero, $edicao, $editora, $descricao,$caminhoLivroEletronico);
                $livroEletronicoDao = LivroEletronicoDao::getInstance();
                $retorno = $livroEletronicoDao->salvaLivro($livro, $id_dono);
            }
        }catch(Exception $e){
            print"<script>alert('".$e->getMessage()."')</script>";
            echo "<script>window.location='../Visao/cadastrarLivro.php';</script>";
            exit;    
        }
        return $retorno;
    }
    
    public function pesquisaLivro($titulo){
        $livroFisicoDao = LivroFisicoDao::getInstance();
        
        $listaLivrosMatriz = $livroFisicoDao->pesquisaLivroDao($titulo);
        
        $livros = Array();
        
        foreach($listaLivrosMatriz as $livro){
            if(strcmp($livro['caminhoLivroEletronico'], 'NSA') == 0){
                $livroObjeto = LivroControlador::criaObjetoLivroFisico($livro['titulo_livro'], $livro['autor'], 
                $livro['genero'], $livro['edicao'], $livro['editora'], $livro['venda'], 
                $livro['troca'], $livro['estado_conserv'], $livro['descricao_livro']);
            } else {
                $livroObjeto = LivroControlador::criaObjetoLivroEletronico($livro['titulo_livro'], $livro['autor'], 
                $livro['genero'], $livro['edicao'], $livro['editora'], $livro['descricao_livro'], $livro['caminhoLivroEletronico']);
            }
            array_push($livros, $livroObjeto);
        }

        //return $listaLivrosMatriz;
        return $livros;
    }
    
    public function getLivroById($id){
        
        $livroFisicoDao = LivroFisicoDao::getInstance();
        
        $atributosLivro = $livroFisicoDao->getLivroById($id);
        
        $livro = LivroControlador::criaObjetoLivroFisico($atributosLivro['titulo_livro'], $atributosLivro['autor'], 
                $atributosLivro['genero'], $atributosLivro['edicao'], $atributosLivro['editora'], $atributosLivro['venda'], 
                $atributosLivro['troca'], $atributosLivro['estado_conserv'], $atributosLivro['descricao_livro']);
        return $livro;
    }
    
    public function deletaLivro($idLivro){
        return LivroDao::deletaLivro($idLivro);
        }
    
    public function alteraLivro($titulo, $autor, $genero, $edicao, $editora, $venda, $troca, $estado, $descricao, $id_dono, $id_usuario){
        if(empty($venda) && empty($troca)){
            $venda = "venda";
            $troca = "troca";
        }

        try{
            $livro = new Livro($titulo, $autor, $genero, $edicao, $editora, $venda, $troca, $estado, $descricao);
        }catch(Exception $e){
            print"<script>alert('".$e->getMessage()."')</script>";
            echo "<script>window.location='../Visao/cadastrarLivro.php';</script>";
            exit;    
        }
        return LivroDao::alteraLivro($livro, $id_dono, $id_usuario);
    }
    
    public function recuperaLivroPorIdUsuario($idUsuario){
        $livroFisicoDao = LivroFisicoDao::getInstance();
        return $livroFisicoDao->recuperaLivroPorIdUsuarioDao($idUsuario);
    }
    
    public function pegaTodosLivros($id_dono){
        $livroFisicoDao = LivroFisicoDao::getInstance();
        
        return $livroFisicoDao->pegaTodosLivrosDao($id_dono);
    }
    
    public function criaObjetoLivroFisico($titulo, $autor, $genero, $edicao, $editora, $venda, $troca, $estado, $descricao){
        $livro = new LivroFisico($titulo, $autor, $genero, $edicao, $editora, $venda, $troca, $estado, $descricao);
        return $livro;
    }
    
    public function criaObjetoLivroEletronico($titulo, $autor, $genero, $edicao, $editora, $descricao, $caminhoDiretorio){
        $livro = new LivroEletronico($titulo, $autor, $genero, $edicao, $editora, $descricao, $caminhoDiretorio);
        return $livro;
    }
}
