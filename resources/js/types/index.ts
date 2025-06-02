export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
}

export interface Review {
    id: number;
    user_id: number;
    product_id: number;
    rating: number;
    comment: string;
    created_at: string;
    updated_at: string;
    user: User;
} 