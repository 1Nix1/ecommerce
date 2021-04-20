$(function(){
    $('#categoria-escolhida').onclick(function(){
        var categoria = $(this).val();

        if(categoria != ''){
            var dados = {
                palavra : categoria
            }
            $.post('novo_registro_produto.php', dados, function(retorna){
                $(".resultado").html(retorna);
            });
        }
        
    });
});