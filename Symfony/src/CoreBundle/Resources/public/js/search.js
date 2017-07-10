/**
 * Created by leo on 01/07/17.
 */
$(function(){

    //display map
    var mymap = L.map('mapid').setView([46.52863469527167, 2.43896484375], 5);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoibGVvZ3JhbWJlcnQiLCJhIjoiY2o0bHFvZWZsMTR2czMxbXo1OWlvamw5cSJ9.H0OEY_8Tu0Me5hgunS7CQw'
    }).addTo(mymap);

    var $familyField = $('select#familles');
    var $orderField = $('select#ordres');
    var $birdField = $('#birdField');

    //function to clear map
    $('#clearMap').click(function (e) {
        e.preventDefault();
        location.reload();
    });

    //function to sort families by order
     $orderField.on('change', function (e) {
         e.preventDefault();

         var submit = function(){
             var $orderFieldUrl = '/search/order/'+$orderField.val();
            //returns an ajax call
             return $.ajax({
                 url: $orderFieldUrl,
                 method: 'GET'
             }).done(function (response) {
                 //If it's a success, we clean family field before to add results in it
                 $('#familles').empty().append('<option></option>');
                 $('#birdField').val('');
                 $.each(response.families, function(key, value){
                     var $toAdd="<option value='"+value.famille+"'>"+value.famille+"</option>";
                     $('#familles').append($toAdd);
                 })
             }).error(function(){
                 console.log('Erreur ajax appel données table Species');
             })
         };
         submit();
     });

     //function which does exactly the same thing than previously, but for birds (sort by family)
    $familyField.on('change', function (e) {
        e.preventDefault();
        var submit = function(){

            var $familyFieldUrl = '/search/family/'+$familyField.val();

            return $.ajax({
                url: $familyFieldUrl,
                method: 'GET'
            }).done(function (response) {
                $('#birds').empty();
                $('#birdField').val('');
                var $nameBirds = [];
                $.each(response.birds, function(key, value){
                    if(value.nomVern !== ''){
                        if(jQuery.inArray(value.nomVern, $nameBirds) !== -1){
                            console.log('Cet oiseau est déjà dans la liste');
                        } else {
                            $nameBirds.push(value.nomVern);
                            var $toAdd="<option value='"+value.nomVern+" - "+value.id+"'>";
                            $('#birds').append($toAdd);
                        }
                    }
                });
                console.log($nameBirds);
            }).error(function(){
                console.log('Erreur ajax appel données table Species');
            })
        };
        submit();
    });

    //function to get all observations for a bird
    $birdField.on('change', function(e){
        e.preventDefault();
        //Get bird id
        var $birdFieldSplit = $birdField.val().split('- ');
        //Call ajax
        var submit = function(){
            var $birdFieldUrl = '/search/bird/accepted/'+$birdFieldSplit[1];
            return $.ajax({
                url: $birdFieldUrl,
                method: 'GET'
            }).done(function(response){
                $('#errorMsg').remove();
                $.each(response.observations, function(key, value){
                    var date = new Date(value.date.date);
                    date = (date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear());
                    $('#mapid').after('<p>Observation faite le <span id="date'+value.id+'">'+date+'</span> par (xxx) aux coordonnées suivantes : <span id="lat'+value.id+'">' + value.latitude+'</span> - <span id="lat'+value.id+'">'+value.longitude+'</span></p>')
                    var marker = L.marker([value.latitude, value.longitude]).addTo(mymap);
                    marker.bindPopup("<b>"+value.bird.nomVern+" observé le "+date+" par "+value.user.username+"</b>");
                })
            }).fail(function(jqXHR, exception){
                var msg = '';
                if (jqXHR.status === 404) {
                    msg = 'Espèce non présente dans le référentiel TAXREF de l\'INPN.';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (jqXHR.status === 422) {
                    msg = 'Cette espèce n\'a pas encore été observée.';
                } else {
                    msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                }
                $('#errorMsg').remove();
                $('form').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
            })
        };
        submit();
    });

    //Get gps coordinates from controller to display marker for an untreated observation
    var $latGPS = $('.alert-success_lat').html();
    var $lonGPS = $('.alert-success_lon').html();
    if ($latGPS !== "" && $lonGPS !== ""){
        var marker = L.marker([$latGPS, $lonGPS]).addTo(mymap);
        marker.bindPopup("<b>Observation non publiée, en attente de validation");
    }
});