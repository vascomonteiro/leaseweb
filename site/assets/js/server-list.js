var dataSource;
var ramList;


$(function() {
    $.ajax({
        method: "GET",
        url: "/api/servers",
        })
    .done(function( data ) {
        dataSource = data.data;
        console.log( "Data refreshed: " + data.msg );
        createTable(dataSource);
        populateLocations(data.location);
        ramList = data.ram;
        populateRAM(data.ram);
        setSlider(data.rangeHDDCapacity[0], data.rangeHDDCapacity[1]);
        $("#submitBtnFilter").trigger( "click" );
    });
});

//Slider
function setSlider(min, max) {
    $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: min,
        max: max,
        from: min,
        to: max,
        grid: true,
        skin: "big",
    });
}
    
function createTable(dataSource){
    new DataTable('#serverTable', {
        data: dataSource,
        columns: [
            { data: 'Model', width: 300 },
            { data: 'RAM' },
            { data: 'HDD' },
            { data: 'HDDGB', width: 50 },
            { data: 'Location' , width: 200},
            { data: 'Price' },
        ],
    });
}

function populateLocations(location){
    var option = '<option value="">Select location</option>';
    $("#locationInput option").remove();
    for (var item in location) {
        console.log(location[item]);
        option += '<option value="'+ location[item] + '">' + location[item] + '</option>';
    }
    $('#locationInput').append(option);
}



function populateRAM(ramList){
    var checkBoxItem = '';
    $("#ramList").empty();
    for (var item in ramList) {
        checkBoxItem += '<input type="checkbox" class="btn-check px-1 autoUpdate" id="btn-check-ram-'+ramList[item]+'" checked autocomplete="off"><label class="btn btn-outline-primary m-1" for="btn-check-ram-'+ramList[item]+'">'+ramList[item]+'</label>';
    }
    $('#ramList').append(checkBoxItem);
}

$("#submitBtnFilter").on("click", function() {
    var ramArrayFilter = [];
    for (const element of ramList) {
        if ($('#btn-check-ram-'+element).is(":checked")){
            ramArrayFilter.push(element);
        }
    }
    $.ajax({
        method: "GET",
        url: "/api/servers",
        data: { location:  $("#locationInput").val(), hdd: $("#hddInput").val(), ram: ramArrayFilter.toString(), rangeSlider: $(".js-range-slider").val() }
    }).done(function( data ) {
        console.log( "Data Refreshed: " + data.msg );
        $('#serverTable').DataTable().clear().rows.add(data.data).draw();
    });
});

// $(".autoUpdate").on("change ", function() {
//     $("#submitBtnFilter").trigger( "click" );
// });

