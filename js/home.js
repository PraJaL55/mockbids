function enterMockbid(username, houseId, mockbid_price){
	$.ajax({
	  url: "exe/insertMockbidPrice.php?houseId="+houseId+"&mockbid_price="+mockbid_price+"&username="+username,
	  success: function( result ) {
		$('#mockbid-modal-'+houseId).css("display","none");
		$('#mockbid-p-'+houseId).html("Your Mockbid Price: $"+mockbid_price);
	  }
	});
}

function enterSellingPrice(houseId, selling_price){
	$.ajax({
	  url: "exe/insertSellingPrice.php?houseId="+houseId+"&selling_price="+selling_price,
	  success: function( result ) {
		$('#mockbid-modal-'+houseId).css("display","none");
		$('#mockbid-p-'+houseId).html("Selling Price: $"+selling_price);
	  }
	});
}