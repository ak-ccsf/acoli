import React from 'react';
import '../App.css';
import './SearchQualityOfLiveSection.css';

function SearchQualityOfLiveSection() {
    return (
        <div>
            <img source src="../images/img-15.jpg"/>
            <div className='search-quality'>
                <div className='search-quality-container'>
                    <h1> Quality of Live </h1>
                    <span>Cost of Living scores and indexes are a way to compare the overall price of goods and services between different countries. You get a custom cost of living that includes housing, food, utilities, transportation, healthcare costs, taxes, and child care prices.</span>
                    <div className='search-quality-wrapper'>
                        <form action='/' method='get'>
                            <input
                                type='search'
                                className='search-quality-field'
                                placeholder='Enter a City or Zip'
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default SearchQualityOfLiveSection;