import React from 'react';
import '../App.css';
import './SearchSection.css'

function SearchSection() {
    return (
        <div className='search-container'>

            <video autoPlay muted loop > 
                <source src="../videos/video-2.mp4" type="video/mp4" /> 
            </video> 
            <h1> Search Best Places To Live </h1>
            <div>
                <form action='/' method='get'>
                    <input
                        type='search'
                        className='search-field'
                        placeholder='Enter a City or Zip'
                    />
                </form>
            </div>

        </div>
    )
}

export default SearchSection;