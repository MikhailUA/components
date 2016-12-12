<!DOCTYPE>

<html>

  <head>
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>


    <style>
      #cy {
          width: 100%;
          height: 100%;
          display: block;
      }
    </style>


  </head>
  <body>

  <div id="cy"></div>

  <script>

    var cyJson =   {
      elements: {
        nodes: [
          { data: { id: 'j', name: 'Jerry' } },
          { data: { id: 'e', name: 'Elaine' } },
        ],
        edges: [
          { data: { id: 'je', source: 'j', target: 'e' } },
        ]
      }
    };

    var cy = cytoscape(
      {
        container: document.getElementById('cy'), // container to render in

        style: [ // the stylesheet for the graph
          {
            selector: 'node',
            style: {
              'background-color': '#666',
              'label': 'data(id)'
            }
          },

          {
            selector: 'edge',
            style: {
              'curve-style': 'bezier',
              'width': 3,
              'line-color': '#ccc',
              'target-arrow-color': '#ccc',
              'target-arrow-shape': 'triangle',
              'line-color': '#ccc',
            }
          }
        ]
      }
    );

    cy.json(cyJson);
    cy.add([
      { group: "nodes", data: { id: "n0" }, position: { x: 100, y: 100 } },
      { group: "nodes", data: { id: "n1" }, position: { x: 200, y: 200 } },
      { group: "edges", data: { id: "e0", source: "n0", target: "n1" } },
      { group: "edges", data: { id: "e10", source: "n0", target: "j" } }
    ]);


/*
    cy.layout({
      name: 'grid',
     // rows: 1,
      padding: 10,
      directed: true,
    });*/




    /*
     var cy = cytoscape(
     {
     container: document.getElementById('cy'), // container to render in

     elements: [ // list of graph elements to start with
     { // node a
     data: { id: 'a' }
     },
     { // node b
     data: { id: 'b' }
     },
     { // edge ab
     data: { id: 'ab', source: 'a', target: 'b' }
     }
     ],

     style: [ // the stylesheet for the graph
     {
     selector: 'node',
     style: {
     'background-color': '#666',
     'label': 'data(id)'
     }
     },

     {
     selector: 'edge',
     style: {
     'width': 3,
     'line-color': '#ccc',
     'target-arrow-color': '#ccc',
     'target-arrow-shape': 'triangle'
     }
     }
     ],

     layout: {
     name: 'grid',
     rows: 1
     }
     }
     );
     */


  </script>


  </body>

</html>