function itzuliHautapenarenTestuaEtaDenbora(hizlariak) {
    
    var emaitza = {};
    
    emaitza.nodoak = [];
    emaitza.ordezkatutako_aurrizkia = "";
    
    var nodoak = getSelectedNodes();
    var nodo_kop = nodoak.length;
    
    var hautatutako_testua = getSelText();
    
    var tmp_denbora;
    var tmp_testua;
    
    // Aurrizkia ordezkatzen badugu, ordezkatua true izango da eta aurrizkia aldagaian gordeko dugu.
    var ordezkatuta = false;
    var aurrizkia = "";
    
    // select text function
    function getSelText() {
        var txt = '';
        if (window.getSelection){
            txt = window.getSelection();
        }
        else if (document.getSelection){
            txt = document.getSelection();
        }
        else if (document.selection){
            txt = document.selection.createRange().text;
        }          
        
        return (txt + "").trim();
    }
    
    // Hurrengo 3 funtzioak hemendik hartu ditut:
    // http://stackoverflow.com/questions/7781963/js-get-array-of-all-selected-nodes-in-contenteditable-div
    function nextNode(node) {
        if (node.hasChildNodes()) {
            return node.firstChild;
        } else {
            while (node && !node.nextSibling) {
                node = node.parentNode;
            }
            if (!node) {
                return null;
            }
            return node.nextSibling;
        }
    }
    
    function getRangeSelectedNodes(range) {
        
        var node = range.startContainer;
        var endNode = range.endContainer;
        
        // Special case for a range that is contained within a single node
        if (node == endNode) {
            return [node];
        }
        
        // Iterate nodes until we hit the end container
        var rangeNodes = [];
        while (node && node != endNode) {
            rangeNodes.push( node = nextNode(node) );
        }
        
        // Add partially selected nodes at the start of the range
        node = range.startContainer;
        while (node && node != range.commonAncestorContainer) {
            rangeNodes.unshift(node);
            node = node.parentNode;
        }
        
        return rangeNodes;
        
    }
    
    function getSelectedNodes() {
        
        if (window.getSelection) {
            var sel = window.getSelection();
            if (!sel.isCollapsed) {
                return getRangeSelectedNodes(sel.getRangeAt(0));
            }
        }
        
        return [];
        
    }
    
    // Nodoen arraya garbituko dugu, hitzak bakarrik uzteko.
    // http://stackoverflow.com/questions/9882284/looping-through-array-and-removing-items-without-breaking-for-loop
    while (nodo_kop--) {
        
        if (nodoak[nodo_kop].nodeType !== Node.TEXT_NODE) {
            
            nodoak.splice(nodo_kop, 1);
            
        } else if (nodoak[nodo_kop].textContent === " \n") {
            
            nodoak.splice(nodo_kop, 1);
            
        }
        
    }
    
    console.log(nodoak);
    
    for (var i = 0; i < nodoak.length; i++) {
        
        tmp_testua = nodoak[i].textContent;
        tmp_denbora = nodoak[i].parentNode.getAttribute("data-ms");
        
        // Testuaren hasieran hizlariaren aurrizkia badago kendu.
        for (var i = 0; i < hizlariak.length; i++) {
            
            // Aurrizkia ordezkatu dugun ala ez jakiteko replace-ri funtzio bat pasako diogu.
            tmp_testua = tmp_testua.replace(hizlariak[i].aurrizkia, function() {
                ordezkatua = true;
                emaitza.ordezkatutako_aurrizkia = hizlariak[i].aurrizkia;
                return "";
            });
            
        }
        
        // Lehen hitza osorik badago bakarrik onartuko dugu.
        if (i === 0 && hautatutako_testua.indexOf(tmp_testua) !== 0) {
            
            // Hitzaren bukaerako koma, puntu, galdera ikurra eta abar hautatu gabe badaude hitza onartuko dugu.
            // Ikur batekin bukatzen den hitz bakarreko hautapenetan, adibidez, "proba," hona sartu behar luke.
            if (hautatutako_testua.indexOf(tmp_testua.slice(0, -1), hautatutako_testua.length - tmp_testua.length) !== -1
                && (tmp_testua.charAt(tmp_testua.length - 1) === "."
                    || tmp_testua.charAt(tmp_testua.length - 1) === ","
                    || tmp_testua.charAt(tmp_testua.length - 1) === "!"
                    || tmp_testua.charAt(tmp_testua.length - 1) === "?")) {
                
                // Hitzaren bukaerako ikurra kendu eta emaitzara gehituko dugu.
                emaitza.nodoak.push({
                    denbora: tmp_denbora,
                    testua: tmp_testua.slice(0, -1)
                })
                
            }
            
            continue;
            
        // Azken hitzarekin berdin.
        } else if (i === nodoak.length - 1
                   && hautatutako_testua.indexOf(tmp_testua, hautatutako_testua.length - tmp_testua.length) === -1) {
            
            // Hitzaren bukaerako koma, puntu, galdera ikurra eta abar hautatu gabe badaude hitza onartuko dugu.
            // Ikur batekin bukatzen den hitz anitzeko hautapenetan, adibidez, "beste proba," hona sartu behar luke.
            if (hautatutako_testua.indexOf(tmp_testua.slice(0, -1), hautatutako_testua.length - tmp_testua.length) !== -1
                && (tmp_testua.charAt(tmp_testua.length - 1) === "."
                    || tmp_testua.charAt(tmp_testua.length - 1) === ","
                    || tmp_testua.charAt(tmp_testua.length - 1) === "!"
                    || tmp_testua.charAt(tmp_testua.length - 1) === "?")) {
                
                // Hitzaren bukaerako ikurra kendu eta emaitzara gehituko dugu.
                emaitza.nodoak.push({
                    denbora: tmp_denbora,
                    testua: tmp_testua.slice(0, -1)
                })
                
            }
            
            continue;
            
        }
        
        emaitza.nodoak.push({
            denbora: tmp_denbora,
            testua: tmp_testua
        })
        
    }
    
    console.log(emaitza.nodoak);
    
    return emaitza;
}