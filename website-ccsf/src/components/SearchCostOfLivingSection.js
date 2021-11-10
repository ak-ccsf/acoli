import React from 'react';
import '../App.css';
import './SearchCostOfLivingSection.css';

function SearchCostOfLivingSection() {
    return (
        <div>
            <img source src="../images/img-10.jpg"/>
            <div className='search-cost'>
                <div className='search-cost-container'>
                    <h1> Cost of living </h1>
                    <span>Cost of Living scores and indexes are a way to compare the overall price of goods and services between different countries. You get a custom cost of living that includes housing, food, utilities, transportation, healthcare costs, taxes, and child care prices.</span>
                    <div className='search-cost-wrapper'>
                        <form action='/' method='get'>
                            <input
                                type='search'
                                className='search-cost-field'
                                placeholder='Enter a City or Zip'
                            />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default SearchCostOfLivingSection;