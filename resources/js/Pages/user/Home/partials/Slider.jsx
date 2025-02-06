import React, { useRef, useState } from 'react';
import { Navigation } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/react';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';

const Slider = () => {
    const swiperRef = useRef(null);
    
    return (
        <div className='position-relative'>
            <Swiper onSwiper={(swiper) => (swiperRef.current = swiper)} modules={[Navigation]} className="mySwiper rounded-circle position-relative" style={{ border: '6px solid #4b208c', overflow: 'hidden' }}>
                <SwiperSlide className='w-100 h-100'>
                    <img src='http://127.0.0.1:8000/landingPage/images/slider-img.jpg' style={{ objectFit: 'cover' }} />
                </SwiperSlide>
                <SwiperSlide className='w-100 h-100'>
                    <img src='http://127.0.0.1:8000/landingPage/images/slider-img.jpg' style={{ objectFit: 'cover' }} />
                </SwiperSlide>
                <SwiperSlide className='w-100 h-100'>
                    <img src='http://127.0.0.1:8000/landingPage/images/slider-img.jpg' style={{ objectFit: 'cover' }} />
                </SwiperSlide>

            </Swiper>

            <div className='position-absolute navigate-buttons' style={{  }}>
                <div class="swiper-button-prev position-relative" onClick={() => swiperRef.current?.slidePrev()}></div>
                <div class="swiper-button-next position-relative" onClick={() => swiperRef.current?.slideNext()}></div>
            </div>

        </div>

    )
}

export default Slider