import React from 'react';
import '../App.css';
import './SearchCostOfLivingSection.css'

function SearchCostOfLivingSection() {
    return (
        <div className='search-cost'>
            <div className='search-cost-container'>
            <h1> Cost of living </h1>
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
    )
}

export default SearchCostOfLivingSection;