$(document).ready(function () {
    $(".startSection").hide();
    
    $("#startPage").click(function () {
        $(".mainSection").hide();
        $(".startSection").show();
        $("#startPage").addClass("active");
        $("#mainPage").removeClass("active");
    });
    
    $("#showStartPage,#showStartPage1,#showStartPage2").click(function(){
        $(".mainSection").hide();
        $(".startSection").show();
        $("#startPage").addClass("active");
        $("#mainPage").removeClass("active");
        });
    
    $("#mainPage").click(function () {
        $(".startSection").hide();
        $(".mainSection").show();
        $("#mainPage").addClass("active");
        $("#startPage").removeClass("active");
    });
    
});