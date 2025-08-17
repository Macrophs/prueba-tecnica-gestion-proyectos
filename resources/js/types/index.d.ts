import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

interface UserType {
    id: number;
    name: string;
    username: string;
    avatar?: string;
    role: string;
    created_at:string;
}
interface ProjectType {
    id: number;
    name: string;
    user_id: number;
    user: UserType;
    description: string;
    created_at: string;
    updated_at: string;
    completion_percentage: string;
    tasks_by_status?: Record<string, number>;
}

interface TaskType {
    id: number;
    title: string;
    description: string;
    project_id: string
    user_id?: string;
    user?: UserType;
    status: "Pendiente" | "En Proceso" | "Finalizada";
    start_date: string;
    end_date: string;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
