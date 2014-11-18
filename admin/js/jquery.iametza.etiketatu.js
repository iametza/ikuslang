// Mendekotasunak:
//  * bootstrap-tagmanager.js (http://welldonethings.com/tags/manager)
//  * bootstrap-tagmanager.css
//  * bootstrap-typeahead.js v2.3.2 (http://twitter.github.com/bootstrap/javascript.html#typeahead)
;(function($) {
    
    var eskuratuEtiketak = function($obj, path, id, mota) {
       
        $.ajax({
            
            type: "GET",
            dataType: "json",
            url: path,
            data: {
                id: id,
                mota: mota
            }
            
        }).done(function(data, textStatus, jqXHR) {
            
            // Eskuratutako etiketak bistaratu.
            $obj.tagsManager({
                
                prefilled: data.etiketak,
                hiddenTagListId: data.hiddenTagListId
                
            });
            
        }).fail(function(jqXHR, textStatus, errorThrown) {
            
            console.log("Errore bat gertatu da zerbitzaritik etiketak eskuratzean.");
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            
        })
        
    }
    
    // https://gist.github.com/rjhilgefort/6234462
    var typeaheadAjax = function($obj, path) {
        // use this hash to cache search results
        $obj.query_cache = {};
        
        $obj.typeahead({
            
            source:function (query, process) {
            
                // if query is in cache use cached return
                if($obj.query_cache[query]){
                    return process($obj.query_cache[query]);
                }
                
                //if searching is defined, it hasn't been executed yet but the user typed something else
                if( typeof searching != "undefined") {
                    clearTimeout(searching);
                    process([]);
                }
                
                //define a timeout to wait 300ms before searching
                searching = setTimeout(function() {
                    return $.getJSON(
                        path,
                        { query: query },
                        function (data) {
                            // save result to cache, remove next line if you don't want to use cache
                            $obj.query_cache[query] = data.etiketak;
                            // only search if stop typing for 300ms aka fast typers
                            return process(data.etiketak);
                        }
                    );
                }, 300);
            }
        });
    }
    
    $.fn.etiketatu = function(data) {
        
        return this.each(function() {
            
            // Id-a eta mota definituta badaude bakarrik eskuratuko ditugu etiketak.
            // Id eta motarik zehazten ez bada etiketen zerrenda itzultzen du APIak.
            if (data.id > 0 && data.mota) {
                
                eskuratuEtiketak($(this), data.bidea, data.id, data.mota);
                
            } else {
                
                // tagsManager hasieratu.
                $(this).tagsManager({
                    hiddenTagListId: data.hiddenTagListId
                });
                
            }
            
            typeaheadAjax($(this), data.bidea);
            
        });
        
    }
}(jQuery));