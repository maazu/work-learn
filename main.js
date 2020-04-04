
var app = new Vue({
	el: '#app',
	data:{
		errorMsg: "",
		successMsg : "",
		showAddModal: false,
		showEditModal: false,
		showDeleteModal: false,
		staff : [],
		newStaff: { title: "", username: "", forename: "", surname: "", email: "", type: "", password: "", lng_only: ""},
		currentStaff: {}
	},
	mounted: function(){
		this.getAllUsers();
	},
	methods: {
		getAllUsers(){
			axios.get("http://localhost/tomorrowtest3/homedir/public_html/Staff/process_data.php?request=showAllStaff").then(function(response){
				if(response.data.error){
					app.errorMsg = response.data.message;
				}
				else{
					app.staff =  response.data.Staff;	
				}
			});
		},
		addNewStaff(){
			// var formData = app.toFormData(app.newStaff);
			

			// let formData = new FormData();
			// formData.append("title", this.newStaff.title);
			// formData.append("username", this.newStaff.username);
			// formData.append("forname", this.newStaff.forename);
			// formData.append("surname", this.newStaff.surname);
			// formData.append("email", this.newStaff.email);
			// formData.append("type", this.newStaff.type);
			// formData.append("password", this.newStaff.password);
			// formData.append("lng_only", this.newStaff.lng_only);



			axios.get('http://localhost/tomorrowtest3/homedir/public_html/Staff/process_data.php?request=addNewStaff', {
			  params: {
			  	 "title": this.newStaff.title,
				"username": this.newStaff.username,
				"forname": this.newStaff.forename,
				"surname": this.newStaff.surname,
				"email": this.newStaff.email,
				"type": this.newStaff.type,
				"password": this.newStaff.password,
				"lng_only": this.newStaff.lng_only
			  }
			}).then(function(response){
			        //handle success
			        app.newStaff =  { title: "", username: "", forename: "", surname: "", email: "", type: "", password: "", lng_only: ""};
			        app.successMsg = response.data.Message;	
					app.getAllUsers();
			        console.log(response);
			    })
			    .catch(function (response) {
			        //handle error
			        app.errorMsg = response.data.Message;
					console.log("error");
					
			        console.log(response);
			   });
		},

		updateStaff(){
			// var formData = app.toFormData(app.newStaff);
			axios.get('http://localhost/vuejs/process_Data.php?request=updateStaff', {
			  params: {
			  	 "title": this.currentStaff.title,
				"username": this.currentStaff.username,
				"forname": this.currentStaff.forename,
				"surname": this.currentStaff.surname,
				"email": this.currentStaff.email,
				"type": this.currentStaff.type,
				"password": this.currentStaff.password,
				"lng_only": this.currentStaff.lng_only
			  }
			}).then(function(response){
			        //handle success
			       if(response.data.error){
						app.errorMsg = response.data.Message;
						console.log("error");
					}
					else {
						 app.newStaff =  { title: "", username: "", forename: "", surname: "", email: "", type: "", password: "", lng_only: ""};
						app.successMsg = response.data.Message;
						console.log("success");		
						app.getAllUsers();
					}
				})
			    .catch(function (response) {
			        //handle error
			        app.errorMsg = response.data.Message;
					console.log("error");
					
			        console.log(response);
			   });
		},

		
		deleteStaff(){
			// var formData = app.toFormData(app.newStaff);

			axios.get('', {
			  params: {
				"username": this.currentStaff.username,
			  }
			}).then(function(response){
					
				   	app.currentStaff =  { title: "", username: "", forename: "", surname: "", email: "", type: "", password: "", lng_only: ""};
					if(response.data.error){
						app.errorMsg = response.data.Message;
						console.log("error");
					}
					else {
						app.successMsg = response.data.Message;
						console.log("success");		
						app.getAllUsers();
					}
				})
				.catch(err => {
					console.log(err);
				});
		},
		selectStaff(staff){	
			app.currentStaff = staff;
			console.log(app.currentStaff);
		}
		
	}
});

