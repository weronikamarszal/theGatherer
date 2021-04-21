import React from 'react';
import '../../index.css';
import itemImage from '../../itemsImages/stampImage.png';

class ThisCollection extends React.Component {

    render(){

        return(
            <div>
                <div className='thisCollectionTitle'>
                    <h1>This collection</h1>
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



                </div>
            </div>
        );
    }
}


export default ThisCollection;