function enterMockbid(username, houseId, mockbid_price){
	console.log(username);
	$.ajax({
	  url: "exe/insertMockbidPrice.php?houseId="+houseId+"&mockbid_price="+mockbid_price+"&username="+username,
	  success: function( result ) {
		$('#mockbid-modal-'+houseId).css("display","none");
		$('#mockbid-p-'+houseId).html("Your Mockbid Price: $"+mockbid_price);
	  }
	});
}