import 'gojs';
export class workflow {
    addDiagramListener(event) {
        let button = document.getElementById('SaveButton');

        if (button) {
            button.disabled = !this.myDiagram.isModified;
        }
        let idx = document.title.indexOf('*');

        if (this.myDiagram.isModified) {
            if (idx < 0) {
                document.title = document.title + '*';
            }
        } else if (idx >= 0) {
            document.title = document.title.substr(0, idx);
        }
    }

    nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding('location', 'loc', go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                // the Node.location is at the center of each node
                'locationSpot': go.Spot.Center
            }
        ];
    }

    nodeStyleStartEnd() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding('location', 'loc', go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                'deletable'   : false,
                // the Node.location is at the center of each node
                'locationSpot': go.Spot.Center
            }
        ];
    }

    textStyle() {
        return {
            'font'  : 'bold 11pt Helvetica, Arial, sans-serif',
            'stroke': 'whitesmoke'
        }
    }


    init() {
        if ($('.workflow').length == 0) {
            return;
        }

        $('#SaveButton').on('click', this.save.bind(this));
        $('#LoadButton').on('click', this.load.bind(this));
        $('#printButton').on('click', this.printDiagram.bind(this));
        if (window.goSamples) {
            goSamples();
        } // init for these samples -- you don't need to call this
        let flowchart = go.GraphObject.make; // for conciseness in defining templates

        this.myDiagram = flowchart(go.Diagram, 'myDiagramDiv', // must name or refer to the DIV HTML element
            {
                'LinkDrawn'            : showLinkLabel, // this DiagramEvent listener is defined below
                'LinkRelinked'         : showLinkLabel,
                'undoManager.isEnabled': true // enable undo & redo
            } );
        // when the document is modified, add a "*" to the title and enable the "Save" button

        this.myDiagram.addDiagramListener('Modified', this.addDiagramListener.bind(this));
        // helper definitions for node templates
        // Define a function for creating a "port" that is normally transparent.
        // The "name" is used as the GraphObject.portId,
        // the "align" is used to determine where to position the port relative to the body of the node,
        // the "spot" is used to control how links connect with the port and whether the port
        // stretches along the side of the node,
        // and the boolean "output" and "input" arguments control whether the user can draw links from or to the port.
        function makePort(name, align, spot, output, input) {
            let horizontal = align.equals(go.Spot.Top) || align.equals(go.Spot.Bottom);
            // the port is basically just a transparent rectangle that stretches along the side of the node,
            // and becomes colored when the mouse passes over it

            return flowchart(go.Shape, {
                'fill'        : 'transparent', // changed to a color in the mouseEnter event handler
                'strokeWidth' : 0, // no stroke
                'width'       : horizontal ? NaN : 8, // if not stretching horizontally, just 8 wide
                'height'      : !horizontal ? NaN : 8, // if not stretching vertically, just 8 tall
                'alignment'   : align, // align the port on the main Shape
                'stretch'     : (horizontal ? go.GraphObject.Horizontal : go.GraphObject.Vertical),
                'portId'      : name, // declare this object to be a "port"
                'fromSpot'    : spot, // declare where links may connect at this port
                'fromLinkable': output, // declare whether the user may draw links from here
                'toSpot'      : spot, // declare where links may connect at this port
                'toLinkable'  : input, // declare whether the user may draw links to here
                'cursor'      : 'pointer', // show a different cursor to indicate potential link point
                'mouseEnter'  : function (e, port) { // the PORT argument will be this Shape
                    if (!e.diagram.isReadOnly) {
                        port.fill = 'rgba(255,0,255,0.5)';
                    }
                },
                'mouseLeave': function (e, port) {
                    port.fill = 'transparent';
                }
            } );
        }
        // define the Node templates for regular nodes
        this.myDiagram.nodeTemplateMap.add('', // the default category
            flowchart(go.Node, 'Table', this.nodeStyle(),
                // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
                flowchart(go.Panel, 'Auto',
                    flowchart(go.Shape, 'Rectangle', {
                            'fill'       : '#00A9C9',
                            'strokeWidth': 0
                        },
                        new go.Binding('figure', 'figure')),
                    flowchart(go.TextBlock, this.textStyle(), {
                            'margin'  : 8,
                            'maxSize' : new go.Size(160, NaN),
                            'wrap'    : go.TextBlock.WrapFit,
                            'editable': true
                        },
                        new go.Binding('text').makeTwoWay())
                ),
                // four named ports, one on each side:
                makePort('T', go.Spot.Top, go.Spot.TopSide, false, true),
                makePort('L', go.Spot.Left, go.Spot.LeftSide, true, true),
                makePort('R', go.Spot.Right, go.Spot.RightSide, true, true),
                makePort('B', go.Spot.Bottom, go.Spot.BottomSide, true, false)
            ));
        this.myDiagram.nodeTemplateMap.add('Conditional',
            flowchart(go.Node, 'Table', this.nodeStyle(),
                // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
                flowchart(go.Panel, 'Auto',
                    flowchart(go.Shape, 'Diamond', {
                            'fill'       : '#00A9C9',
                            'strokeWidth': 0
                        },
                        new go.Binding('figure', 'figure')),
                    flowchart(go.TextBlock, this.textStyle(), {
                            'margin'  : 8,
                            'maxSize' : new go.Size(160, NaN),
                            'wrap'    : go.TextBlock.WrapFit,
                            'editable': true
                        },
                        new go.Binding('text').makeTwoWay())
                ),
                // four named ports, one on each side:
                makePort('T', go.Spot.Top, go.Spot.Top, false, true),
                makePort('L', go.Spot.Left, go.Spot.Left, true, true),
                makePort('R', go.Spot.Right, go.Spot.Right, true, true),
                makePort('B', go.Spot.Bottom, go.Spot.Bottom, true, false)
            ));
        this.myDiagram.nodeTemplateMap.add('Start',
            flowchart(go.Node, 'Table', this.nodeStyleStartEnd(),
                flowchart(go.Panel, 'Auto',
                    flowchart(go.Shape, 'Circle', {
                        'minSize'    : new go.Size(40, 40),
                        'fill'       : '#79C900',
                        'strokeWidth': 0
                    } ),
                    flowchart(go.TextBlock, 'Start', this.textStyle(),
                        new go.Binding('text'))
                ),
                // three named ports, one on each side except the top, all output only:
                makePort('L', go.Spot.Left, go.Spot.Left, true, false),
                makePort('R', go.Spot.Right, go.Spot.Right, true, false),
                makePort('B', go.Spot.Bottom, go.Spot.Bottom, true, false)
            ));
        this.myDiagram.nodeTemplateMap.add('End',
            flowchart(go.Node, 'Table', this.nodeStyleStartEnd(),
                flowchart(go.Panel, 'Auto',
                    flowchart(go.Shape, 'Circle', {
                        'minSize'    : new go.Size(40, 40),
                        'fill'       : '#DC3C00',
                        'strokeWidth': 0
                    } ),
                    flowchart(go.TextBlock, 'End', this.textStyle(),
                        new go.Binding('text'))
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort('T', go.Spot.Top, go.Spot.Top, false, true),
                makePort('L', go.Spot.Left, go.Spot.Left, false, true),
                makePort('R', go.Spot.Right, go.Spot.Right, false, true)
            ));
        // taken from ../extensions/Figures.js:
        go.Shape.defineFigureGenerator('File', function (shape, w, h) {
            let geo = new go.Geometry();
            let fig = new go.PathFigure(0, 0, true); // starting point

            geo.add(fig);
            fig.add(new go.PathSegment(go.PathSegment.Line, 0.75 * w, 0));
            fig.add(new go.PathSegment(go.PathSegment.Line, w, 0.25 * h));
            fig.add(new go.PathSegment(go.PathSegment.Line, w, h));
            fig.add(new go.PathSegment(go.PathSegment.Line, 0, h).close());
            let fig2 = new go.PathFigure(0.75 * w, 0, false);

            geo.add(fig2);
            // The Fold
            fig2.add(new go.PathSegment(go.PathSegment.Line, 0.75 * w, 0.25 * h));
            fig2.add(new go.PathSegment(go.PathSegment.Line, w, 0.25 * h));
            geo.spot1 = new go.Spot(0, 0.25);
            geo.spot2 = go.Spot.BottomRight;
            return geo;
        } );
        // replace the default Link template in the linkTemplateMap
        this.myDiagram.linkTemplate = flowchart(go.Link, // the whole link panel
            {
                'routing'       : go.Link.AvoidsNodes,
                'curve'         : go.Link.JumpOver,
                'corner'        : 5,
                'toShortLength' : 4,
                'relinkableFrom': true,
                'relinkableTo'  : true,
                'reshapable'    : true,
                'resegmentable' : true,
                // mouse-overs subtly highlight links:
                'mouseEnter'    : function (e, link) {
                    link.findObject('HIGHLIGHT').stroke = 'rgba(30,144,255,0.2)';
                },
                'mouseLeave': function (e, link) {
                    link.findObject('HIGHLIGHT').stroke = 'transparent';
                },
                'selectionAdorned': false
            },
            new go.Binding('points').makeTwoWay(),
            flowchart(go.Shape, // the highlight shape, normally transparent
                {
                    'isPanelMain': true,
                    'strokeWidth': 8,
                    'stroke'     : 'transparent',
                    'name'       : 'HIGHLIGHT'
                } ),
            flowchart(go.Shape, // the link path shape
                {
                    'isPanelMain': true,
                    'stroke'     : 'gray',
                    'strokeWidth': 2
                },
                new go.Binding('stroke', 'isSelected', function (sel) {
                    return sel ? 'dodgerblue' : 'gray';
                } ).ofObject()),
            flowchart(go.Shape, // the arrowhead
                {
                    'toArrow'    : 'standard',
                    'strokeWidth': 0,
                    'fill'       : 'gray'
                } ),
            flowchart(go.Panel, 'Auto', // the link label, normally not visible
                {
                    'visible'        : false,
                    'name'           : 'LABEL',
                    'segmentIndex'   : 2,
                    'segmentFraction': 0.5
                },
                new go.Binding('visible', 'visible').makeTwoWay(),
                flowchart(go.Shape, 'RoundedRectangle', // the label shape
                    {
                        'fill'       : '#F8F8F8',
                        'strokeWidth': 0
                    } ),
                flowchart(go.TextBlock, 'Yes', // the label
                    {
                        'textAlign': 'center',
                        'font'     : '10pt helvetica, arial, sans-serif',
                        'stroke'   : '#333333',
                        'editable' : true
                    },
                    new go.Binding('text').makeTwoWay())
            )
        );
        // Make link labels visible if coming out of a "conditional" node.
        // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
        function showLinkLabel(e) {
            let label = e.subject.findObject('LABEL');

            if (label !== null) {
                label.visible = (e.subject.fromNode.data.category === 'Conditional');
            }
        }
        // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
        this.myDiagram.toolManager.linkingTool.temporaryLink.routing   = go.Link.Orthogonal;
        this.myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;
        this.load(); // load an initial diagram from some JSON text
        // initialize the Palette that is on the left side of the page
        this.myPalette = flowchart(go.Palette, 'myPaletteDiv', // must name or refer to the DIV HTML element
            {
                'nodeTemplateMap': this.myDiagram.nodeTemplateMap, // share the templates used by this.myDiagram
                'model'          : new go.GraphLinksModel([ // specify the contents of the Palette
                    {
                        'category': 'Start',
                        'text'    : 'Start'
                    },
                    {
                        'text': 'Step'
                    },
                    {
                        'category': 'Conditional',
                        'text'    : '???'
                    },
                    {
                        'category': 'End',
                        'text'    : 'End'
                    }
                ])
            } );
    } // end init
    // Show the diagram's model in JSON format that the user may edit
    save() {
        document.getElementById('mySavedModel').value = this.myDiagram.model.toJson();
        this.myDiagram.isModified                     = false;
    }

    load() {
        this.myDiagram.model = go.Model.fromJson(document.getElementById('mySavedModel').value);
    }
    // print the diagram by opening a new window holding SVG images of the diagram contents for each page
    printDiagram() {
        let svgWindow = window.open();

        if (!svgWindow) {
            return;
        } // failure to open a new Window
        let printSize = new go.Size(700, 960);
        let bnds      = this.myDiagram.documentBounds;
        let x         = bnds.x;
        let y         = bnds.y;

        while (y < bnds.bottom) {
            while (x < bnds.right) {
                let svg = this.myDiagram.makeSVG( {
                    'scale'   : 1.0,
                    'position': new go.Point(x, y),
                    'size'    : printSize
                } );

                svgWindow.document.body.appendChild(svg);
                x = x + printSize.width;
            }
            x = bnds.x;
            y = y + printSize.height;
        }
        setTimeout(function () {
            svgWindow.print();
        }, 1);
    }
    constructor() {}
}
