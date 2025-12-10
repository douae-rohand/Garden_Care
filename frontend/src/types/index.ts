export interface Intervenant {
    id: number
    name: string
    email: string
    phone: string
    profileImage: string
    location: string
    memberSince: string
}

export interface Service {
    id: string
    name: string
    color: string
}

export interface SubService {
    id: string
    type: string
    name: string
    hourlyRate: number
    description: string
    active: boolean
    archived: boolean
    completedJobs: number
    materials: string[]
}

export interface Reservation {
    id: number
    clientName: string
    clientImage: string
    service: string
    task: string
    date: string
    time: string
    duration: string
    hourlyRate: number
    location: string
    status: 'pending' | 'accepted' | 'refused' | 'completed'
    message?: string
}

export interface RegularAvailability {
    day: string
    available: boolean
    startTime: string
    endTime: string
}

export interface SpecialAvailability {
    id: number
    date: string
    available: boolean
    startTime?: string
    endTime?: string
    reason?: string
}

export type DashboardTab = 'profile' | 'services' | 'myservices' | 'reservations' | 'availability' | 'reviewclients' | 'reviewsstats' | 'history'
