/**
* jQuery.colorize
* @version 1.7.0
* Copyright (c) 2008-2012 Eric Karimov - ekarim57(at)gmail(dot)com
* http://jquerycolorize.blogspot.com/

* Dual licensed under MIT and GPL.
* Date: 13/01/2012
*
* @projectDescription Table colorize using jQuery.
*
* @author Eric Karimov, contributor Aymeric Augustin
*
* @param {altColor, bgColor, hoverColor, hoverClass, hiliteColor, hiliteClass, oneClick, columns,  banColumns}
* altColor : alternate row background color, 'none' can be used for no alternate background color
* bgColor : background color (The default background color is white).
* hoverColor : background color when you hover a mouse over a row
* hoverClass: style class for mouseover; takes precedence over hoverColor property; may slow down performance in IE
* hiliteColor : row highlight background color, 'none' can be used for no highlight
* hiliteClass: style class for highlighting a row or a column; takes precedence over the hiliteColor setting
* oneClick : true/false(default) -	 if true, clicking a new row reverts the current highlighted row to the original background color
* columns : true/false/'hover', 'rowHover'  - The default is false. if true, highlights columns instead of rows. If the value is 'hover',
* 	 a column is highlighted on mouseover, but does not respond to clicking. Instead, a row is highlighted when clicked.
* If the value is 'rowHover', a row is highlighted on mouseover, and a column is highlighted on click.
* banColumns : []	- columns not to be highlighted or hovered over; supply an array of column indices, starting from 0;
* 'last' value can be used to ban the last column
* banDataClick: true/false(default); if true, you can only click on the header rows
* ignoreHeaders:true(default)/false; if true, table headers are not colorized
* nested : true/false(default); use if you have nested tables in your main table
* @return {jQuery} Returns the same jQuery object, for chaining.
*
* @example $('#tbl').colorize();
*
* @$('#tbl').colorize({bgColor:'#EAF6CC', hoverColor:'green', hiliteColor:'red', columns:true, banColumns:[4,5,'last']});
*
* @$('#tbl').colorize({ columns : 'hover', oneClick:true});
* All the parameters are optional.
*/

jQuery.fn.colorize = function(params) {
    options = {
        altColor: '#ECF6FC',
        bgColor: '#fff',
        hoverColor: '#BCD4EC',
        hoverClass: '',
        hiliteColor: '#98d600',
        hiliteClass: '',
        oneClick: false,
        columns: false,
        banColumns: [],
        banDataClick: false,
        ignoreHeaders: true,
        nested: false,
        rowStart: 0
    };
    jQuery.extend(options, params);

    var colorHandler = {

        addHoverClass: function() {
            this.origColor = this.style.backgroundColor;
            this.style.backgroundColor = '';
            jQuery(this).addClass(options.hoverClass);
        },

        addBgHover: function() {
            this.origColor = this.style.backgroundColor;
            this.style.backgroundColor = options.hoverColor;
        },

        removeHoverClass: function() {
            jQuery(this).removeClass(options.hoverClass);
            this.style.backgroundColor = this.origColor;
        },

        removeBgHover: function() {
            this.style.backgroundColor = this.origColor;
        },

        checkHover: function() {
            if (!this.onfire) this.hover();
        },

        checkHoverOut: function() {
            if (!this.onfire) this.removeHover();
        },

        highlight: function() {
            if (options.hiliteClass.length > 0 || options.hiliteColor != 'none') {
                if (!this.onfire & options.columns == 'hover')
                    this.origColor = this.style.backgroundColor;

                this.onfire = true;

                if (options.hiliteClass.length > 0) {
                    this.style.backgroundColor = '';
                    jQuery(this).addClass(options.hiliteClass).removeClass(options.hoverClass);
                }
                else if (options.hiliteColor != 'none') {
                    this.style.backgroundColor = options.hiliteColor;
                }
            }
        },
        stopHighlight: function() {
            this.onfire = false;
            this.style.backgroundColor = (this.origColor) ? this.origColor : '';
            jQuery(this).removeClass(options.hiliteClass).removeClass(options.hoverClass);
        }
    }


    function processCells(cells, idx, func) {
        var colCells = getColCells(cells, idx);

        jQuery.each(colCells, function(index, cell2) {
            func.call(cell2);
        });

        function getColCells(cells, idx) {
            var arr = [];
            for (var i = 0; i < cells.length; i++) {
                if (cells[i].cellIndex == idx)
                    arr.push(cells[i]);
            }
            return arr;
        }
    }

    function processAdapter(cells, cell, func) {
        processCells(cells, cell.cellIndex, func);
    }


    var clickHandler = {
        toggleColumnClick: function(cells) {
            var func = (!this.onfire) ? colorHandler.highlight : colorHandler.stopHighlight;
            processAdapter(cells, this, func);
        },

		getElem: function(){
			return (options.columns == true||options.columns == 'hover')?
            	   jQuery(this).parent().get(0): this;
		},

        toggleRowClick: function(elems) {

			var row = clickHandler.getElem.call(this);
            if (!row.onfire)
                colorHandler.highlight.call(row);
            else
                colorHandler.stopHighlight.call(row);
        },

        oneClick: function(cell, cells, indx) {

         if (cells.clicked != null) {
                if (cells.clicked == indx) // repeat the same set click
                {
                    this.stopHilite();
                    cells.clicked = null; //  set was not selected
                }
                else {
                    this.stopHilite();
                    this.hilite.call(cell);
                }
            }
            else if (cells.clicked == null) {
                this.hilite.call(cell);
            }
        },

        oneColumnClick: function(cells) {

            var indx = this.cellIndex;
            clickHandler.hilite = hilite;
            clickHandler.stopHilite = stopHilite;
            clickHandler.oneClick(this, cells, indx);

            function stopHilite() {
                processCells(cells, cells.clicked, colorHandler.stopHighlight);
            }
            function hilite() {
                processAdapter(cells, this, colorHandler.highlight);
                cells.clicked = indx;
            }
        },

        oneRowClick: function(elems) {

            var row = clickHandler.getElem.call(this);
            var indx = row.rowIndex;
            clickHandler.hilite = hilite;
            clickHandler.stopHilite = stopHilite;
            clickHandler.oneClick(this, elems, indx);

            function stopHilite() {
                colorHandler.stopHighlight.call(clickHandler.tbl.rows[elems.clicked]); // delete the selected row
            }
            function hilite() {
                colorHandler.highlight.call(row); // the current row is set to select
                elems.clicked = indx; //the current row is saved
            }
        }
    }

    function isDataCell() {
        return (options.columns==true || options.columns=='hover')?
        		(this.nodeName == 'TD'):
        		(this.nodeName=='TR');
    }

    function checkBan() {
        return (jQuery.inArray(this.cellIndex, options.banColumns) != -1);
    }

    function attachHoverHandler() {
        this.hover = optionsHandler.hover;
        this.removeHover = optionsHandler.removeHover;
    }

    function handleColumnHoverEvents(cell, cells) {
        attachHoverHandler.call(cell);
        cell.onmouseover = function() {
            if (checkBan.call(this)) return;
            processAdapter(cells, this, colorHandler.checkHover);
        }
        cell.onmouseout = function() {
            if (checkBan.call(this)) return;
            processAdapter(cells, this, colorHandler.checkHoverOut);
        }
    }

	function handleRowHoverEvents(row, rows) {

	        //row = jQuery(cell).parent().get(0);
	        attachHoverHandler.call(row);
	        row.onmouseover = colorHandler.checkHover;
	        row.onmouseout = colorHandler.checkHoverOut;
    }


    var optionsHandler = {
        getHover: function() {
            if (options.hoverClass.length > 0) {
                this.hover = colorHandler.addHoverClass;
                this.removeHover = colorHandler.removeHoverClass;
            }
            else {
                this.hover = colorHandler.addBgHover;
                this.removeHover = colorHandler.removeBgHover;
            }
        },

        getRowClick: function() {
            if (options.oneClick)
                return clickHandler.oneRowClick;
            else
                return clickHandler.toggleRowClick;
        },

        getColumnClick: function() {
            if (options.oneClick)
                return clickHandler.oneColumnClick;
            else
                return clickHandler.toggleColumnClick;
        }
    }

    var rowHandler = {
        handleHoverEvents: handleRowHoverEvents,
        clickFunc: optionsHandler.getRowClick()
    }

    var colHandler = {
        handleHoverEvents: handleColumnHoverEvents,
        clickFunc: optionsHandler.getColumnClick()
    }

    return this.each(function() {
        if (options.altColor != 'none') {
            var odd, even;
            odd = even = (options.ignoreHeaders) ? 'tr:has(td)' : 'tr';
            if (options.rowStart > 0)
                odd = even = (even += ":gt(" + options.rowStart + ")");

            if (options.nested) {
                odd += ':nth-child(odd)';
                even += ':nth-child(even)';
            }
            else {
                odd += ':odd';
                even += ':even';
            }
            jQuery(this).find(odd).css('background', options.bgColor);
            jQuery(this).find(even).css('background', options.altColor);
        }

        var elems, findSelector;

        //        if (jQuery(this).find('thead tr:last th').length > 0)
        //            elems = jQuery(this).find('td, thead tr:last th');
        //        else
        //            elems = jQuery(this).find('td,th');

		if (options.columns) {
        	if (options.rowStart > 0)
        	    findSelector = "tr:gt(" + options.rowStart + ") td";
        	else
        	    findSelector = "tr td";

		}
		else{
			if (options.rowStart > 0)
			    findSelector = "tr:gt(" + options.rowStart + ")";
			else
        	    findSelector = "tr";
		}

        elems = jQuery(this).find(findSelector);
        elems.clicked = null;

        if (jQuery.inArray('last', options.banColumns) != -1) {
            if (this.rows.length > 0) {
                options.banColumns.push(this.rows[0].elems.length - 1);
            }
        }

        optionsHandler.getHover();
        clickHandler.tbl = this;

        if (options.columns) {
            var handler = colHandler;
            if (options.columns == 'hover')
                handler.clickFunc = optionsHandler.getRowClick();
            else if (options.columns == 'rowHover')
                handler.handleHoverEvents = handleRowHoverEvents;
        }
        else {
            var handler = rowHandler;
        }

        jQuery.each(elems, function(i, cell) {
            handler.handleHoverEvents(this, elems);
            $(this).bind("click", function() {
                if (checkBan.call(this)) return;
                if (options.banDataClick && isDataCell.call(this)) return;
                handler.clickFunc.call(this, elems);
            });
        });
    });
}

