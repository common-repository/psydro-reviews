// Polyfill for Old Browser
if (typeof Object.create !== "function") {
    Object.create = function(obj) {
        function F() {};
        F.prototype = obj;
        return new F();
    }
}

(function($, window, document, undefined) {
        var obj = '';

        var psydroReviewImageUploader = {

            init: function(options, elem, callbackFunc) {
            var self = this;



            self.elem = elem;

            self.options = { };
         

            self.callback = callbackFunc;
 
            self.options = $.extend({ }, $.fn.psydroReviewImageUpload.options, options);
            self.options.validExt = self.options.validExt.split('|');

            self.fileInputId = '#file_'+$(self.elem).attr('name');

        
            self.run();
        },

        run: function() {
            var self = this;
            self.options.fileCount = 0 ;
            $(document).on('change',  self.fileInputId, function(event) {
            
                self.fileElem = document.getElementById("file_"+$(self.elem).attr('name'));

                if( self.options.limit != false ){
                    self.options.fileCount = self.fileElem.files.length + self.options.fileCount;
                    if(self.options.limit < self.options.fileCount || self.options.limit < self.fileElem.files.length) {
                        self.options.fileCount = self.options.fileCount-self.fileElem.files.length;
                        alert('You can not upload more than 3 images');
                        return false;
                    }
                }
                
                self.initializeThumbs( event );

            });



            $(self.elem).click(function() {
                $(self.fileInputId).replaceWith($(self.fileInputId).val('').clone(true));
                $(self.fileInputId).trigger('click');
            });

            $(document).on('click', '.psydro-remove', function() {
                self.options.fileCount--;
                $(this).parents('.pysdo-unverified-image-thumb').remove() ;  
            });
            
            self.initModal();
        },

        initModal: function() {
            var self = this;

            accepted = multiple = '';

            $(self.options.validExt).each(function(index, value) {
                subfix = ( index == 0 )? '': ',';
                accepted += subfix+'image/'+value;
            });
            
            if(self.options.multipleFiles == true) {
                multiple = 'multiple'; 
            }

          
           $('body').prepend( "<div class='extra-fields'><input style='display:none;' accept='"+accepted+"' type='file' "+ multiple +" name='"+$(self.elem).attr('name')+"' id='file_"+ $(self.elem).attr('name') +"'> </div>" )
            
        },

        pushImage: function() {
            var self = this;
         
            return $.ajax({
                method: 'POST',
                url: self.options.ajaxUrl,
                data: self.pushData,
                dataType: 'json'
            });
        },

        initializeCroppie: function() {

            var self = this;

            $('#psydro-image-crop-container').html("");
            var el = document.getElementById('psydro-image-crop-container');

            if(obj != undefined && obj != '') {
                obj.destroy;
            }
            
            width = $('.modal-body').innerWidth() -20 ;
            height =  self.options.height - 20;

            if( width >  self.options.width ) {
                width = self.options.width;
            }

            calculated_height =  (width/ (self.options.width/self.options.height) ); 

            obj = new Croppie(el, {
                viewport: {
                    width: parseInt( width ),
                    height: calculated_height, //3.63:1 (width/height) ),
                    type: self.options.type,    
                    showZoomer: self.options.showZoomer,
                    enableOrientation: self.options.enableOrientation,
                },
                boundary: {
                    width: parseInt( width  ) + 10,
                    height: parseInt( calculated_height ) + 10,
                },
                enableExif: false
            }); 

             if (self.fileElem.files && self.fileElem.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $(self.options.modalElem).addClass('ready');
                    obj.bind({
                        url: e.target.result
                    });
                }
                reader.readAsDataURL(self.fileElem.files[0]);
            }   

           self.getModalFooter();

            document.querySelector('#save-or-update').addEventListener('click', function (ev) {
                button =  $('#save-or-update');
                current_text = button.text();

                button.attr('disabled', 'disabled').html('Loading...');


                $('.psydro-image-crop').modal('show').show();

                obj.result({
                    type: 'canvas',
                    quality: self.options.quality, 
                    format: 'jpg',
                    size: { height: self.options.height, width: self.options.width }
                }).then(function (blob) {

                   if( !self.options.ajaxUrl ){
                        $('#psydro-crop-modal').modal('hide');
                        
                        $(self.options.resultElem).html('<input type="hidden" id="'+$(self.elem).attr('data-attr-id')+'" name="'+$(self.elem).attr('name')+'[]" value="'+blob+'">');
                        $(self.options.resultElem).css('background', 'url('+blob+')' )
                        
                        if(typeof self.callback == "function")
                                    self.callback(blob, self.elem);  

                    }else{
                        self.pushData = self.getDataAttributes( self.elem ); 
                        self.pushData.image = blob;

                        if(self.options.resultElem) {
                            $('#psydro-crop-modal').modal('hide');
                            $(self.options.resultElem).addClass('img-loader');
                        }

                        var response = self.pushImage( self.pushData );
                        
                        response.done(function(response){
                            if(response.status == 1 ){
                                
                                if(self.options.resultElem) {
                                    $(self.options.resultElem).removeClass('img-loader').css({'background-image': 'url('+ blob +')'});
                                }

                                if(typeof self.callback == "function")
                                    self.callback(blob, self.elem);  
                            }else{
                                alert('Error in ajax request');
                            }
                            button.removeAttr('disabled').html(current_text);
                        });
  
                    }
                });

            });         
        },
        compress: function(source_img_obj, quality, output_format ) {
            var source_img_obj = source_img_obj ? source_img_obj: null;
            var quality = quality ? quality: 30;
            var output_format = output_format ? output_format: 'jpg';
            return jic.compress(source_img_obj, quality, output_format);
        },
        initializeThumbs: function( event ) {

            var self = this;

           

            var files = event.target.files; //FileList object
            var output = document.getElementById(self.options.thumbWrapWithIn);

            //clear og content box
            $('#og-status').html("");
            var filtered_files = [];

            for( var i = 0; i< files.length; i++) {
                file = files[i];

                if( file.size > 5000000 ){
                        alert('You can not upload more than 5MB');
                        self.options.fileCount--;
                        continue;
                }else{
                   is_valid_file = false;
                   $.each( self.options.validExt, function( index, value ) {
                        if(file.type == 'image/'+value){
                            is_valid_file = true;
                        }
                   } );
                   if( ! is_valid_file ){
                    alert('You can not upload ' + file.type );
                    self.options.fileCount--;
                    continue;
                   }
                }

                filtered_files.push( file );

            }
            
            self.options.processFileCount += filtered_files.length; 

             if(self.options.disabledBtnClass && filtered_files.length >= 1 ) {
               $(self.options.disabledBtnClass).attr('disabled', 'disabled');
            }
            
            $.each(filtered_files, function( index, file ) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    if(self.options.ajaxUrl) {
                        self.create_box(e, file );
                    }else{
                        self.compress( e.target.result ).done( function (compressedImgSrc) {
                            compressedImgSrc = compressedImgSrc.src;
                            thumbSkeleton = '<div class="pysdo-unverified-image-thumb"><figure><img  src="'+ compressedImgSrc +'"><input type="hidden" name="'+$(self.elem).attr('name')+'[]" value="'+compressedImgSrc+'"><div class="psydro-overlay"><a href="javascript:void(0)" class="psydro-remove"><i class="zmdi zmdi-delete"></i></a></div></figure></div>' ;
                            if( self.options.multipleFiles == true ){
                                $(self.options.thumbWrapWithIn).append(thumbSkeleton);
                            }else{
                                $(self.options.thumbWrapWithIn).html(thumbSkeleton)
                            }
                        });
                    }
                }
                reader.readAsDataURL( file );
            });
         
            if(self.options.disabledBtnClass) {
               // $(self.options.disabledBtnClass).attr('disabled', 'false');
            }

        },
         create_box: function(e,file){
            var self = this;
            
            var rand = Math.floor((Math.random()*100000)+3);
            var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
            var src     = e.target.result;

            var image = '<img  id="psydro_image_'+ rand +'" src="'+src+'">';
            var template = '<div class="pysdo-unverified-image-thumb" id="psydro_image'+rand+'">';
            template += '<figure>';
            template += image;
            template += '<div class="psydro-progress psydro-uploading-image" id="psydro-progress_'+rand+'"><div class="psydro-progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%"><span class="psydro-sr-only"></span></div></div>';
            template += '<input type="hidden" name="'+$(self.elem).attr('name')+'[]" id="'+rand+'" value=""><div class="psydro-overlay"><a href="javascript:void(0)" class="psydro-remove"><i class="zmdi zmdi-delete"></i></a></div></figure>';
            template += '</div>';
        
            $.when($(self.options.thumbWrapWithIn).append(template)).then( function () {
                setTimeout(function () {
                    imageObj = document.getElementById('psydro_image_' + rand);
                    compressedImgSrc = jic.compress(imageObj, 30, 'jpg').src;
                    self.upload(file,rand, compressedImgSrc);
                }, 500);
            });
        },
        upload: function(file,rand, image){
            var self = this;

            var xhr = new Array();
            xhr[rand] = new XMLHttpRequest();
            xhr[rand].open("post", self.options.ajaxUrl, true);
            
            xhr[rand].upload.addEventListener("progress", function (event) {
                if (event.lengthComputable) {
                    $("#psydro-progress_"+rand+" .psydro-progress-bar").css("width",(event.loaded / event.total) * 100 + "%");
                } else {
                    alert("Failed to compute file upload length");
                }
            }, false);

            xhr[rand].onload = function (oEvent) {  
              if (xhr[rand].readyState === 4 && xhr[rand].status === 200) {  
                    $("#psydro-progress_"+rand+" .psydro-progress-bar").css("width","100%");
                    $("#psydro-progress_"+rand).hide();
                    response = JSON.parse(  xhr[rand].responseText );
                    
                    $('#'+rand).val(response.id);
                    
                    self.options.processedCount++; 

                    $("#psydro-progress_"+rand).removeClass('psydro-uploading-image');
                    $('#psydro-remove-link_'+rand).html('<a href="javascript:void(0)" class="psydro-uploaded-remove" data-default_image="'+ self.options.defaultImage +'">X</a>');
               
                    if(self.options.disabledBtnClass && self.options.processedCount == self.options.processFileCount ) {
                       $(self.options.disabledBtnClass).removeAttr('disabled');
                    }
              }  
            };  
              
            xhr[rand].setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
             
            self.pushData = self.getDataAttributes( self.elem ); 
            self.pushData.image = image;

            xhr[rand].send( $.param(self.pushData) );
        },
        getDataAttributes: function (el) {
            var data = {};
            [].forEach.call(el.attributes, function(attr) {
                if (/^data-/.test(attr.name)) {
                    var camelCaseName = attr.name.substr(5).replace(/-(.)/g, function ($0, $1) {
                        return $1.toUpperCase();
                    });
                    data[camelCaseName] = attr.value;
                }
            });

            return data;
        }
        
    };

    $.extend({
        psydroReviewImageUpload: function(elem, options) {
            $(elem).each(function() {
                var psydroReviewImageUpload = Object.create(psydroReviewImageUploader);
                psydroReviewImageUpload.init(options,  $(elem))
                $.data( $(elem), 'psydroReviewImageUpload', psydroReviewImageUpload);
            });    
        }
    });

    // jQuery Plugin
    $.fn.psydroReviewImageUpload = function(options, callbackFunc) {
        return this.each(function() {
            var psydroReviewImageUpload = Object.create(psydroReviewImageUploader);
            psydroReviewImageUpload.init(options, this, callbackFunc)
            $.data(this, 'psydroReviewImageUpload', psydroReviewImageUpload);
        });
    };

    // Plugin Options
    $.fn.psydroReviewImageUpload.options = {
        width: 250,
        height:250,
        uploadOnSelect: false,
        thumbWrapWithIn: '',
        ajaxUrl: '',
        multipleFiles: false,
        inline: false,
        crop: false,
        format: 'png',
        type: 'canvas',
        quality: 0.5,
        showZoomer: false,
        enableOrientation: true,
        boundaryBorder: 10,
        disabledBtnClass: '',
        validExt: 'jpg|png|jpeg',
        limit: false,
        fileCount: 0,
        processedCount: 0,
        processFileCount: 0,
    };
   

})(jQuery, window, document);
 