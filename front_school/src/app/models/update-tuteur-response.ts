export class UpdateTuteurResponse {
  message!: string;
  data!: {
    id: number;
    user_id: number;
    profession: string;
    telephone: string;
  };
}
