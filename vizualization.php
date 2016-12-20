<!DOCTYPE>

<html>

<head>
    <title>cytoscape-dagre.js demo</title>

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>

    <!-- for testing with local version of cytoscape.js -->
    <!--<script src="../cytoscape.js/build/cytoscape.js"></script>-->

    <script src="https://cdn.rawgit.com/cpettitt/dagre/v0.7.4/dist/dagre.min.js"></script>
    <script src="https://cdn.rawgit.com/cytoscape/cytoscape.js-dagre/1.1.2/cytoscape-dagre.js"></script>

    <style>
        body {
            font-family: helvetica;
            font-size: 14px;
        }

        #cy {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 999;
        }

        h1 {
            opacity: 0.5;
            font-size: 1em;
        }
    </style>

    <script>

        $(function(){
            var cy = window.cy = cytoscape({
                container: document.getElementById('cy'),

                boxSelectionEnabled: false,
                autounselectify: true,

              layout: {
                name: 'grid',
                rows: 2
              },

                  style: [
                      {
                          selector: 'node',
                          style: {
                              'content': 'data(id)',
                              'text-opacity': 0.5,
                              'text-valign': 'center',
                              'text-halign': 'right',
                              'background-color': '#11479e'
                          }
                      },

                      {
                          selector: 'edge',
                          style: {
                              'width': 4,
                              'target-arrow-shape': 'triangle',
                              'line-color': '#9dbaea',
                              'target-arrow-color': '#9dbaea',
                              'curve-style': 'bezier'
                          }
                      }
                  ],

                  elements: { nodes: [{ data: { id: 0 } },{ data: { id: 1 } },{ data: { id: 2 } },{ data: { id: 3 } },{ data: { id: 4 } },{ data: { id: 5 } },{ data: { id: 6 } },{ data: { id: 7 } },], edges: [{ data: { source: 0, target: 1} },{ data: { source: 1, target: 2} },{ data: { source: 1, target: 4} },{ data: { source: 1, target: 5} },{ data: { source: 2, target: 3} },{ data: { source: 2, target: 6} },{ data: { source: 3, target: 2} },{ data: { source: 3, target: 7} },{ data: { source: 4, target: 0} },{ data: { source: 4, target: 5} },{ data: { source: 5, target: 6} },{ data: { source: 6, target: 5} },{ data: { source: 6, target: 7} },{ data: { source: 7, target: 3} },{ data: { source: 7, target: 7} },] },
            });

        });

    </script>
</head>

<body>
<!--<h1>cytoscape-dagre demo</h1>-->

<div id="cy" style="width: 300px; height: 300px; "></div>

</body>

</html>