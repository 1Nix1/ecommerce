$(function() {
    $("#search").keyup(function(){

        var pesquisa = $(this).val();

        //Verificar se há algo digitado
        if(pesquisa != ""){
            var dados = {
                palavra : pesquisa
            }
            $.post('../index.php', dados, function(retorna){
                $(".produtos").html(retorna);
            });
            
        }else{
            $(".produtos").html('');
        }
        
    });
});