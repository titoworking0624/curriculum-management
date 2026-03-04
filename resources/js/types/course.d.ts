export type Curriculum = {
    id: number,
    name: string,
    curriculum_number: number,
    curriculum_code: number,
    content: string,
    checklist:string,
}

export type Chapter = {
    id: number
    name:string
    chapter_number:number
    course_id: number
    curricula:Curriculum[]
}

export type Course = {
    id:number
    name:string
    course_code:number
    chapters:Chapter[]
}

export type Participant = {
    id: number
    name: string
};
