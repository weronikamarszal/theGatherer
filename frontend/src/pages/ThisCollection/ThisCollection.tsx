import React from 'react';
import '../../index.css';
import itemImage from '../../itemsImages/stampImage.png';

class ThisCollection extends React.Component {

    render(){

        return(
            <div>
                <div className='headWrapper'>
                    <div className='thisCollectionTitle'>
                        <h1>This collection</h1>
                    </div>
                    <div className='addButtonWrapper'>
                        <button className='addCollectionButton'>Add Item</button>
                    </div>
                </div>
                <hr className='horizontalLine'/>
                <div className="dropdown">
                    <button className="dropbtn">Sort by...</button>
                    <div className="dropdown-content">
                        <button className='sortButton'>Dimensions</button>
                        <button className='sortButton'>Material</button>
                        <button className='sortButton'>Date</button>
                        <button className='sortButton'>Standard</button>
                    </div>
                </div>
                <div className='itemsList'>
                    <div className='item'>
                        <img className='itemImage' src={itemImage} alt="itemImage"/>
                        <div className='itemDescription'>
                            Old stamp
                        </div>
                        <button className='viewItem'>View</button>
                    </div>

                    <div className='item'>
                        <img className='itemImage' src={itemImage} alt="itemImage"/>
                        <div className='itemDescription'>
                            Old stamp
                        </div>
                        <button className='viewItem'>View</button>
                    </div>

                    <div className='item'>
                        <img className='itemImage' src={itemImage} alt="itemImage"/>
                        <div className='itemDescription'>
                            Old stamp
                        </div>
                        <button className='viewItem'>View</button>
                    </div>

                    {/*<div className='item'>*/}
                    {/*    <img className='itemImage' src={itemImage} alt="itemImage"/>*/}
                    {/*    <div className='itemDescription'>*/}
                    {/*        Old stamp*/}
                    {/*    </div>*/}
                    {/*    <button className='viewItem'>View</button>*/}
                    {/*</div>*/}

                </div>
            </div>
        );
    }
}


export default ThisCollection;