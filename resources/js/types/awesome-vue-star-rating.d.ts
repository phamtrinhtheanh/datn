declare module 'awesome-vue-star-rating' {
    import { DefineComponent } from 'vue';
    const StarRating: DefineComponent<{
        rating: number;
        maxRating: number;
        starSize: number;
        readOnly: boolean;
        showRating: boolean;
    }>;
    export default StarRating;
} 