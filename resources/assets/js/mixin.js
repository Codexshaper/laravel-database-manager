const mixin = {
  data: function() {
      return {
          isDebug: false,
          isLoaded: false,
          databaseErrors: [],
      }
  },
  methods: {
      hasPermission: function(permission){
          let partials = permission.split(".");
          for(let userPermission of this.userPermissions) {
              if (userPermission.prefix == partials[0] && userPermission.slug == partials[1]){
                return true;
              }
          }
          return false;
      },
      loadComponent: function(){
          if(!this.isLoaded) {
              this.$nextTick(() => {
                // Add the component back in
                this.isLoaded = true;
              });
          }
      },
      displayError: function(response) {

        this.databaseErrors = [];

        var data = response.data;
        var errors = data.errors;

        if(this.isDebug) {
          var errorMessage = ""
          // console.log(data);

          if(data.message) {
              errorMessage += data.message;
          }

          if(data.file) {
              errorMessage += " In " +data.file;
          }

          if(data.line) {
              errorMessage += " On line number " +data.line;
          }

          toastr.error(errorMessage);
          this.databaseErrors.push(errorMessage);
        }

        if( errors != undefined ) {
            for(var error of errors) {
                toastr.error(error);
                this.databaseErrors.push(error);
                // console.log(error);
            }
        } 
      }
  }
};

export default mixin;