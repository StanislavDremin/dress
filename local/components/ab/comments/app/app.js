/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

class Comments extends React.Component{
	constructor(props){
		super(props);

	}

	render(){
		return(
			<div className="my_test">
				<h3>TEST</h3>
			</div>
		);
	}
}

$(function () {
	ReactDOM.render(<Comments/>, BX('node_root'));
});