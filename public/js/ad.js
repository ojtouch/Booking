$('#add-image').click(function(){
    //Je récupère le numéro des futures champs de ja vais créer
    const index = +$('#widgets-counter').val();

    //Je récupère le prototype des entry 
    const template = $('#ad_images').data('prototype').replace(/_name/g, index);

    //j'injecte ce code au sein de la div
    $('#ad_images').append(template);

    $('#widgets-counter').val(index +1);

    //j'appelle la fonction pour supprimer les formulaires
    handleDeleteButons();
});

function handleDeleteButons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter(){
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButons();