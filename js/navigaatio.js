/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function NaytaKirjautuminen(){

    $("#main").load("views/kirjaudu.html", function () {
        alert("Load was performed.");
    });
}

function NaytaRekisterointi(){

    $("#main").load("views/register.html", function () {
        alert("Load was performed.");
    });
}

function NaytaKalenterit(){

    $("#main").load("views/reservation_list.html", function () {
        alert("Load was performed.");
    });
}
        
       