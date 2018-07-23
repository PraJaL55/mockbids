function enterMockbid(houseId, mockbid_price){
	$.ajax({
	  url: "exe/insertMockbidPrice.php?houseId="+houseId+"&mockbid_price="+mockbid_price,
	  success: function( result ) {
		$('#mockbid-modal-'+houseId).css("display","none");
		$('#mockbid-p-'+houseId).html("Your Mockbid Price: "+mockbid_price);
	  }
	});
}