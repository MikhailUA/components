<!DOCTYPE>

<html>

  <head>
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>


    <style>
      #cy {
          width: 300px;
          height: 300px;
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
          { data: { id: 'k', name: 'Kramer' } },
          { data: { id: 'g', name: 'George' } }
        ],
        edges: [
          { data: { id: 'je', source: 'j', target: 'e' } },
          { data: { source: 'jk', target: 'k' } },
          { data: { source: 'j', target: 'g' } },
          { data: { source: 'e', target: 'j' } },
          { data: { source: 'e', target: 'k' } },
          { data: { source: 'k', target: 'j' } },
          { data: { source: 'k', target: 'e' } },
          { data: { source: 'k', target: 'g' } },
          { data: { source: 'g', target: 'j' } }
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
              'width': 3,
              'line-color': '#ccc',
              'target-arrow-color': '#ccc',
              'target-arrow-shape': 'triangle'
            }
          }
        ]
      }
    );

    cy.json(data);
    cy.layout({
      name: 'grid',
      padding: 10,
      directed: true,
    });

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